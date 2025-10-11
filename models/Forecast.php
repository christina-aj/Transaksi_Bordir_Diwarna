<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forecast".
 *
 * @property int $forecast_id
 * @property int $barang_produksi_id
 * @property int $periode_forecast Format: YYYYMM (contoh: 202508 untuk Agustus 2025)
 * @property float $nilai_alpha
 * @property float $mape_test
 * @property float $hasil_forecast
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BarangProduksi $barangProduksi
 * @property EoqRop[] $eoqRops
 */
class Forecast extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forecast';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_produksi_id', 'periode_forecast', 'nilai_alpha', 'mape_test', 'hasil_forecast'], 'required'],
            [['barang_produksi_id', 'periode_forecast'], 'integer'],
            [['nilai_alpha', 'mape_test'], 'number'],
            [['hasil_forecast'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            // Validasi format periode (harus 6 digit: YYYYMM)
            ['periode_forecast', 'match', 'pattern' => '/^\d{6}$/', 'message' => 'Periode harus dalam format YYYYMM (contoh: 202508)'],
            // Validasi range bulan (01-12)
            ['periode_forecast', 'validatePeriode'],
            [['barang_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarangProduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
            // Unique constraint untuk barang dan periode
            [['barang_produksi_id', 'periode_forecast'], 'unique', 'targetAttribute' => ['barang_produksi_id', 'periode_forecast'], 'message' => 'Forecast untuk barang dan periode ini sudah ada.'],
        ];
    }

    /**
     * Validasi periode forecast
     */
    public function validatePeriode($attribute, $params)
    {
        $periode = $this->$attribute;
        $bulan = $periode % 100;
        
        if ($bulan < 1 || $bulan > 12) {
            $this->addError($attribute, 'Bulan tidak valid. Harus antara 01-12.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'forecast_id' => 'Forecast ID',
            'barang_produksi_id' => 'Barang Produksi',
            'periode_forecast' => 'Periode Forecast',
            'nilai_alpha' => 'Nilai Alpha',
            'mape_test' => 'MAPE Test (%)',
            'hasil_forecast' => 'Hasil Forecast',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BarangProduksi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangProduksi()
    {
        return $this->hasOne(BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }

    /**
     * Gets query for [[EoqRops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEoqRops()
    {
        return $this->hasMany(EoqRop::class, ['forecast_id' => 'forecast_id']);
    }

    /**
     * Konversi periode forecast ke format yang mudah dibaca
     * Contoh: 202508 menjadi "Agustus 2025"
     * 
     * @return string
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
     * Get nama barang
     * 
     * @return string
     */
    public function getNamaBarang()
    {
        return $this->barangProduksi ? $this->barangProduksi->nama : '-';
    }

    /**
     * Konversi string periode ke integer format YYYYMM
     * Contoh: "2025-08-01" atau "202508" menjadi 202508
     * 
     * @param string $periodeString
     * @return int
     */
    public static function convertPeriodeToInt($periodeString)
    {
        // Jika sudah dalam format integer 6 digit
        if (is_numeric($periodeString) && strlen($periodeString) == 6) {
            return (int)$periodeString;
        }

        // Jika dalam format date string (YYYY-MM-DD atau YYYY-MM)
        $date = strtotime($periodeString);
        if ($date !== false) {
            return (int)date('Ym', $date);
        }

        return 0;
    }

    /**
     * Generate periode berikutnya
     * Contoh: 202508 menjadi 202509, 202512 menjadi 202601
     * 
     * @param int $currentPeriode
     * @return int
     */
    public static function getNextPeriode($currentPeriode)
    {
        $bulan = $currentPeriode % 100;
        $tahun = floor($currentPeriode / 100);

        if ($bulan == 12) {
            // Jika Desember, lanjut ke Januari tahun berikutnya
            return ($tahun + 1) * 100 + 1;
        } else {
            // Tambah 1 bulan
            return $tahun * 100 + ($bulan + 1);
        }
    }
}