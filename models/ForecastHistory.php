<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "forecast_history".
 *
 * @property int $forecast_history_id
 * @property int $barang_produksi_id
 * @property int $periode_forecast
 * @property float $nilai_alpha
 * @property float $mape_test
 * @property int $hasil_forecast
 * @property int|null $data_aktual
 * @property int|null $selisih
 * @property string|null $tanggal_dibuat
 */
class ForecastHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'forecast_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_aktual', 'selisih', 'tanggal_dibuat'], 'default', 'value' => null],
            [['mape_test'], 'default', 'value' => 0],
            [['barang_produksi_id', 'periode_forecast', 'hasil_forecast'], 'required'],
            [['barang_produksi_id', 'periode_forecast', 'hasil_forecast', 'data_aktual', 'selisih'], 'integer'],
            [['nilai_alpha', 'mape_test'], 'number'],
            [['tanggal_dibuat'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'forecast_history_id' => 'Forecast History ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'periode_forecast' => 'Periode Forecast',
            'nilai_alpha' => 'Nilai Alpha',
            'mape_test' => 'Mape Test',
            'hasil_forecast' => 'Hasil Forecast',
            'data_aktual' => 'Data Aktual',
            'selisih' => 'Selisih',
            'tanggal_dibuat' => 'Tanggal Dibuat',
        ];
    }

    public function getBarangProduksi()
    {
        return $this->hasOne(\app\models\BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
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

}
