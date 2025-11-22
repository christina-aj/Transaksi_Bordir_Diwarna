<?php

namespace app\models;

use Yii;

/**
 * Model untuk menyimpan history forecast vs actual
 * Tracking per barang untuk analisis, tapi forecast total dihitung dari agregasi
 *
 * @property int $forecast_history_id
 * @property int|null $barang_produksi_id
 * @property int|null $barang_custom_pelanggan_id
 * @property int $periode_forecast Format: YYYYMM (202301)
 * @property float|null $nilai_alpha
 * @property float|null $nilai_beta
 * @property float|null $nilai_gamma
 * @property float|null $mape_test
 * @property int|null $hasil_forecast Total forecast untuk periode ini
 * @property int|null $data_aktual Data penjualan aktual dari riwayat_penjualan
 * @property int|null $selisih Selisih antara forecast dan aktual
 * @property string|null $tanggal_dibuat
 */
class ForecastHistory extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'forecast_history';
    }

    public function rules()
    {
        return [
            [['barang_produksi_id', 'barang_custom_pelanggan_id'], 'default', 'value' => null],
            [['barang_produksi_id', 'barang_custom_pelanggan_id', 'periode_forecast', 'hasil_forecast', 'data_aktual', 'selisih'], 'integer'],
            [['nilai_alpha', 'nilai_beta', 'nilai_gamma', 'mape_test'], 'number'],
            [['tanggal_dibuat'], 'safe'],
            ['periode_forecast', 'required'],
            ['periode_forecast', 'match', 'pattern' => '/^\d{6}$/'],
            ['periode_forecast', 'validatePeriode'],
            // ['barang_produksi_id', 'exist', 'skipOnError' => true, 'targetClass' => BarangProduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
            // ['barang_custom_pelanggan_id', 'exist', 'skipOnError' => true, 'targetClass' => BarangCustomPelanggan::class, 'targetAttribute' => ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']],
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
            'forecast_history_id' => 'ID',
            'barang_produksi_id' => 'Barang Produksi',
            'barang_custom_pelanggan_id' => 'Barang Custom',
            'periode_forecast' => 'Periode',
            'nilai_alpha' => 'Alpha',
            'nilai_beta' => 'Beta',
            'nilai_gamma' => 'Gamma',
            'mape_test' => 'MAPE (%)',
            'hasil_forecast' => 'Forecast',
            'data_aktual' => 'Aktual',
            'selisih' => 'Selisih',
            'tanggal_dibuat' => 'Tanggal',
        ];
    }

    public function getBarangProduksi()
    {
        return $this->hasOne(BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }

    public function getBarangCustomPelanggan()
    {
        return $this->hasOne(BarangCustomPelanggan::class, ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']);
    }

    /**
     * Format periode untuk tampilan
     * 202301 → "Januari 2023"
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
     * Ambil total data aktual per bulan (agregasi semua barang)
     * Digunakan untuk input Triple Exponential Smoothing
     * AMBIL DARI RIWAYAT_PENJUALAN (format bulan_periode sudah 202301)
     */
    public static function getTotalPerBulan($minMonths = 24)
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
            return null;
        }

        return $data;
    }

    /**
     * Update data aktual untuk periode tertentu
     * Dipanggil saat bulan sudah selesai dan ada data penjualan aktual
     * AMBIL DARI RIWAYAT_PENJUALAN (format bulan_periode sudah 202301)
     */
    // public static function updateDataAktual($periode)
    // {
    //     // Periode format: 202301, 202302, dst (sudah integer 6 digit)
        
    //     // Ambil total penjualan aktual dari riwayat_penjualan
    //     $sql = "
    //         SELECT 
    //             barang_produksi_id,
    //             barang_custom_pelanggan_id,
    //             SUM(qty_penjualan) as total_terjual
    //         FROM riwayat_penjualan
    //         WHERE bulan_periode = :bulan_periode
    //         GROUP BY barang_produksi_id, barang_custom_pelanggan_id
    //     ";

    //     $actualData = Yii::$app->db->createCommand($sql)
    //         ->bindValue(':bulan_periode', $periode)
    //         ->queryAll();

    //     // Update history dengan data aktual
    //     foreach ($actualData as $data) {
    //         // Cek apakah history untuk barang & periode ini sudah ada
    //         $forecastHistory = self::find()
    //             ->where(['periode_forecast' => (int)$periode])
    //             ->one();
    //         // $history = self::find()
    //         //     ->where([
    //         //         'barang_produksi_id' => $data['barang_produksi_id'],
    //         //         'barang_custom_pelanggan_id' => $data['barang_custom_pelanggan_id'],
    //         //         'periode_forecast' => (int)$periode
    //         //     ])
    //         //     ->one();

    //         if (!$history) {
    //             $history = new self();
    //             $history->barang_produksi_id = $data['barang_produksi_id'];
    //             $history->barang_custom_pelanggan_id = $data['barang_custom_pelanggan_id'];
    //             $history->periode_forecast = (int)$periode;
    //             $history->tanggal_dibuat = date('Y-m-d H:i:s');
    //         }

    //         $history->data_aktual = (int)$data['total_terjual'];
            
    //         // Cari forecast untuk periode ini (jika ada)
    //         $forecast = Forecast::find()
    //             ->where(['periode_forecast' => (int)$periode])
    //             ->one();
            
    //         if ($forecast) {
    //             $history->hasil_forecast = $forecast->hasil_forecast;
    //             $history->nilai_alpha = $forecast->nilai_alpha;
    //             $history->nilai_beta = $forecast->nilai_beta;
    //             $history->nilai_gamma = $forecast->nilai_gamma;
    //             $history->mape_test = $forecast->mape_test;
    //             $history->selisih = $history->data_aktual - $history->hasil_forecast;
    //         }
            
    //         $history->save(false);
    //     }
    // }
    public static function updateDataAktual($periode)
    {
        $periode = (int)$periode;

        // Ambil total penjualan aktual
        $sql = "
            SELECT SUM(qty_penjualan) AS total_terjual
            FROM riwayat_penjualan
            WHERE bulan_periode = :bulan_periode
        ";

        $total = Yii::$app->db->createCommand($sql)
            ->bindValue(':bulan_periode', $periode)
            ->queryScalar();

        if ($total === false || $total === null) {
            $total = 0; // boleh ganti null kalau mau
        }

        // Ambil forecast utk periode ini
        $forecast = Forecast::find()
            ->where(['periode_forecast' => $periode])
            ->one();

        if (!$forecast) {
            return false; // tidak ada forecast → skip
        }

        // Cek apakah history sudah ada
        $history = ForecastHistory::find()
            ->where(['periode_forecast' => $periode])
            ->one();

        if (!$history) {
            $history = new ForecastHistory();
            $history->periode_forecast = $periode;
            $history->tanggal_dibuat = date('Y-m-d H:i:s');
        }

        // Isi data aktual dan forecast
        $history->data_aktual = (int)$total;
        $history->hasil_forecast = (int)$forecast->hasil_forecast;
        $history->nilai_alpha = $forecast->nilai_alpha;
        $history->nilai_beta = $forecast->nilai_beta;
        $history->nilai_gamma = $forecast->nilai_gamma;
        $history->mape_test = $forecast->mape_test;
        $history->selisih = $history->data_aktual - $history->hasil_forecast;

        $history->save(false);

        return true;
    }


}