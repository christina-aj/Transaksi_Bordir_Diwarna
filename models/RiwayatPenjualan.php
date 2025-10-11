<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "riwayat_penjualan".
 *
 * @property int $riwayat_penjualan_id
 * @property int $barang_produksi_id
 * @property int $qty_penjualan
 * @property string $bulan_periode
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barangproduksi $barangProduksi
 * @property EoqRop[] $eoqRops
 * @property Forecast[] $forecasts
 */
class RiwayatPenjualan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */

    public $nama;
    public $kode_barang_produksi;

    // /**
    //  * {@inheritdoc}
    //  */

    // public function behaviors()
    // {
    //     return [
    //         [
    //             'class' => TimestampBehavior::className(),
    //             'attributes' => [
    //                 \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
    //                 \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
    //             ],
    //             'value' => new Expression('NOW()'), // or date('Y-m-d H:i:s')
    //         ],
    //     ];
    // }
    
    
    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'riwayat_penjualan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['barang_produksi_id', 'qty_penjualan', 'bulan_periode'], 'required'],
            [['barang_produksi_id', 'qty_penjualan'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['bulan_periode'], 'string', 'max' => 7],
            [['barang_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangproduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'riwayat_penjualan_id' => 'Riwayat Penjualan ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'qty_penjualan' => 'Qty Penjualan',
            'bulan_periode' => 'Bulan Periode',
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
        return $this->hasOne(Barangproduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }

    /**
     * Gets query for [[EoqRops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEoqRops()
    {
        return $this->hasMany(EoqRop::class, ['riwayat_penjualan_id' => 'riwayat_penjualan_id']);
    }

    /**
     * Gets query for [[Forecasts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getForecasts()
    {
        return $this->hasMany(Forecast::class, ['riwayat_penjualan_id' => 'riwayat_penjualan_id']);
    }


    public function getBulanPeriode()
    {
        if (empty($this->bulan_periode)) {
            return '-';
        }
        
        $bulan = $this->bulan_periode % 100;
        $tahun = floor($this->bulan_periode / 100);
        
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $namaBulan[$bulan] . ' ' . $tahun;
    }

}
