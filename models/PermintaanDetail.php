<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permintaan_detail".
 *
 * @property int $permintaan_detail_id
 * @property int|null $permintaan_id
 * @property int|null $barang_produksi_id
 * @property int|null $barang_custom_pelanggan_id
 * @property int|null $qty_permintaan
 * @property string|null $catatan
 *
 * @property PermintaanPelanggan $permintaan
 * @property Barangproduksi $barangProduksi
 * @property BarangCustomPelanggan $barangCustomPelanggan
 */
class PermintaanDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permintaan_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permintaan_id', 'barang_produksi_id', 'barang_custom_pelanggan_id', 'qty_permintaan'], 'integer'],
            [['catatan'], 'string', 'max' => 255],
            [['qty_permintaan'], 'required'],
            // Validasi: salah satu harus diisi (barang_produksi_id atau barang_custom_pelanggan_id)
            ['barang_produksi_id', 'required', 'when' => function($model) {
                return empty($model->barang_custom_pelanggan_id);
            }, 'message' => 'Produk Custom Pelanggan ID cannot be blank.'],
            ['barang_custom_pelanggan_id', 'required', 'when' => function($model) {
                return empty($model->barang_produksi_id);
            }, 'message' => 'Produk Polosan Ready ID cannot be blank.'],
            [['permintaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermintaanPelanggan::class, 'targetAttribute' => ['permintaan_id' => 'permintaan_id']],
            [['barang_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangproduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
            [['barang_custom_pelanggan_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarangCustomPelanggan::class, 'targetAttribute' => ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'permintaan_detail_id' => 'Permintaan Detail ID',
            'permintaan_id' => 'Permintaan ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'barang_custom_pelanggan_id' => 'Barang Custom Pelanggan ID',
            'qty_permintaan' => 'Qty Permintaan',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Gets query for [[Permintaan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermintaan()
    {
        return $this->hasOne(PermintaanPelanggan::class, ['permintaan_id' => 'permintaan_id']);
    }

    // Di model PermintaanDetail.php
    public function getBarangCustomPelanggan()
    {
        return $this->hasOne(BarangCustomPelanggan::class, ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']);
    }

    public function getBarangProduksi()
    {
        return $this->hasOne(BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }

}
