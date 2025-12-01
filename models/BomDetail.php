<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bom_detail".
 *
 * @property int $BOM_detail_id
 * @property int $BOM_barang_id
 * @property int|null $barang_id
 * @property int|null $qty_BOM
 * @property string|null $catatan
 *
 * @property BomBarang $bomBarang
 * @property Barang $barang
 */
class BomDetail extends \yii\db\ActiveRecord
{
    public $nama_barang; // untuk keperluan view/update
    public $kode_barang; // untuk keperluan view/update

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bom_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_produksi_id'], 'required'],
            [['BOM_barang_id', 'barang_produksi_id', 'barang_id'], 'integer'],
            [['qty_BOM'], 'number', 'min' => 0], // ← Float/number
            [['catatan'], 'string', 'max' => 255],
            [['BOM_barang_id'], 'default', 'value' => NULL],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['BOM_barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => BomBarang::class, 'targetAttribute' => ['BOM_barang_id' => 'BOM_barang_id']],
            [['qty_BOM'], 'required', 'message' => 'Jumlah harus diisi'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BOM_detail_id' => 'BOM Detail ID',
            'BOM_barang_id' => 'BOM Barang ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'barang_id' => 'Bahan Baku',
            'qty_BOM' => 'Jumlah (KG)',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Gets query for [[BomBarang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBomBarang()
    {
        return $this->hasOne(BomBarang::class, ['BOM_barang_id' => 'BOM_barang_id']);
    }

    /**
     * Gets query for [[BarangProduksi]] via BomBarang
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangProduksi()
    {
        return $this->hasOne(BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id'])
            ->via('bomBarang');
    }

    /**
     * Gets query for [[Barang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
    }

    // /**
    //  * Get nama barang (bahan baku)
    //  */
    // public function getNamaBarang()
    // {
    //     return $this->barang ? $this->barang->nama_barang : '-';
    // }

    // /**
    //  * Get kode barang (bahan baku)
    //  */
    // public function getKodeBarang()
    // {
    //     return $this->barang ? $this->barang->kode_barang : '-';
    // }
}