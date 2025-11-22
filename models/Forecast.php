<?php

namespace app\models;

use Yii;

/**
 * Model untuk Forecast dengan Triple Exponential Smoothing (Holt-Winters)
 * Menyimpan forecast TOTAL AGREGAT (bukan per barang) untuk 4 bulan ke depan
 *
 * @property int $forecast_id
 * @property int $periode_forecast Format: YYYYMM (202511)
 * @property float $nilai_alpha Parameter level
 * @property float $nilai_beta Parameter trend
 * @property float $nilai_gamma Parameter seasonal
 * @property float $mape_test MAPE untuk validasi
 * @property int $hasil_forecast Total forecast untuk periode ini (pasang)
 * @property int $seasonal_period Periode musiman (default: 12)
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Forecast extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'forecast';
    }

    public function rules()
    {
        return [
            // Kolom barang: optional (backward compatibility)
            [['barang_produksi_id', 'barang_custom_pelanggan_id'], 'default', 'value' => null],
            [['barang_produksi_id', 'barang_custom_pelanggan_id'], 'integer'],
            
            // Validasi utama untuk forecast total
            [['periode_forecast', 'nilai_alpha', 'nilai_beta', 'nilai_gamma', 'hasil_forecast'], 'required'],
            [['periode_forecast', 'hasil_forecast', 'seasonal_period'], 'integer'],
            [['nilai_alpha', 'nilai_beta', 'nilai_gamma', 'mape_test'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            
            // Validasi format periode (harus 6 digit: YYYYMM)
            ['periode_forecast', 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Periode harus dalam format YYYYMM (contoh: 202511)'],
            
            // Validasi range bulan (01-12)
            ['periode_forecast', 'validatePeriode'],
            
            // Unique hanya per periode (bukan per barang)
            ['periode_forecast', 'unique', 'message' => 'Forecast untuk periode ini sudah ada.'],
        ];
    }

    public function validatePeriode($attribute, $params)
    {
        $periode = $this->$attribute;
        $bulan = $periode % 100;
        
        if ($bulan < 1 || $bulan > 12) {
            $this->addError($attribute, 'Bulan tidak valid. Harus antara 01-12.');
        }
    }

    public function attributeLabels()
    {
        return [
            'forecast_id' => 'Forecast ID',
            'periode_forecast' => 'Periode Forecast',
            'nilai_alpha' => 'Alpha (Level)',
            'nilai_beta' => 'Beta (Trend)',
            'nilai_gamma' => 'Gamma (Seasonal)',
            'mape_test' => 'MAPE Test (%)',
            'hasil_forecast' => 'Total Forecast (Pasang)',
            'seasonal_period' => 'Seasonal Period',
            'created_at' => 'Tanggal Dibuat',
            'updated_at' => 'Tanggal Update',
        ];
    }

    /**
     * Format periode untuk tampilan
     * 202511 → "November 2025"
     */
    public function getPeriodeForecastFormatted()
    {
        if (empty($this->periode_forecast)) {
            return '-';
        }

        $bulan = $this->periode_forecast % 100;
        $tahun = floor($this->periode_forecast / 100);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return ($namaBulan[$bulan] ?? '') . ' ' . $tahun;
    }

    /**
     * Generate periode berikutnya
     * 202511 → 202512, 202512 → 202601
     */
    public static function getNextPeriode($currentPeriode)
    {
        $bulan = $currentPeriode % 100;
        $tahun = floor($currentPeriode / 100);

        if ($bulan == 12) {
            return ($tahun + 1) * 100 + 1;
        } else {
            return $tahun * 100 + ($bulan + 1);
        }
    }

    /**
     * Generate periode sebelumnya
     * 202501 → 202412, 202502 → 202501
     */
    public static function getPreviousPeriode($periode)
    {
        $year = (int) substr($periode, 0, 4);
        $month = (int) substr($periode, 4, 2);

        if ($month == 1) {
            $month = 12;
            $year--;
        } else {
            $month--;
        }

        return sprintf("%04d%02d", $year, $month);
    }

    /**
     * Cek apakah bisa generate forecast baru
     * Hanya bisa jika sudah 4 bulan sejak forecast terakhir
     */
    public static function canGenerateNewForecast()
    {
        $lastForecast = self::find()
            ->orderBy(['periode_forecast' => SORT_DESC])
            ->one();

        if (!$lastForecast) {
            return true; // Belum ada forecast, boleh generate
        }

        // Cek apakah sudah 4 bulan sejak forecast terakhir
        $currentPeriode = (int)date('Ym');
        $monthsDiff = self::getMonthsDifference($lastForecast->periode_forecast, $currentPeriode);

        return $monthsDiff >= 4;
    }

    /**
     * Hitung selisih bulan antara dua periode
     */
    private static function getMonthsDifference($periode1, $periode2)
    {
        $year1 = floor($periode1 / 100);
        $month1 = $periode1 % 100;
        $year2 = floor($periode2 / 100);
        $month2 = $periode2 % 100;

        return ($year2 - $year1) * 12 + ($month2 - $month1);
    }

    /**
     * Ambil data historis total per bulan dari riwayat_penjualan
     * Mengelompokkan semua barang dan menjumlahkan per bulan
     * Format bulan_periode sudah 202301 (6 digit integer)
     */
    public static function getHistoricalTotalData($minMonths = 24)
    {
        $sql = "
            SELECT 
                bulan_periode as periode_forecast,
                SUM(qty_penjualan) as total
            FROM riwayat_penjualan
            GROUP BY bulan_periode
            ORDER BY bulan_periode ASC
        ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();

        if (count($data) < $minMonths) {
            return null; // Data tidak cukup
        }

        return $data;
    }
}