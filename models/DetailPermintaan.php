<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "detail_permintaan".
 *
 * @property int $detail_permintaan_id
 * @property int|null $permintaan_penjualan_id
 * @property int|null $barang_produksi_id
 * @property int|null $qty_permintaan
 * @property int|null $catatan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barangproduksi $barangProduksi
 * @property PermintaanPenjualan $permintaanPenjualan
 */
class DetailPermintaan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'detail_permintaan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permintaan_penjualan_id', 'barang_produksi_id', 'qty_permintaan', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['permintaan_penjualan_id', 'barang_produksi_id', 'qty_permintaan'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangproduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
            [['permintaan_penjualan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermintaanPenjualan::class, 'targetAttribute' => ['permintaan_penjualan_id' => 'permintaan_penjualan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'detail_permintaan_id' => 'Detail Permintaan ID',
            'permintaan_penjualan_id' => 'Permintaan Penjualan ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'qty_permintaan' => 'Qty Permintaan',
            'catatan' => 'Catatan',
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
     * Gets query for [[PermintaanPenjualan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermintaanPenjualan()
    {
        return $this->hasOne(PermintaanPenjualan::class, ['permintaan_penjualan_id' => 'permintaan_penjualan_id']);
    }

}
