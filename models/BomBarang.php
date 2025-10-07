<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bom_barang".
 *
 * @property int $BOM_barang_id
 * @property int|null $barang_produksi_id
 * @property int|null $total_bahan_baku
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property BomDetail[] $bomDetails
 * @property Barangproduksi $barangProduksi
 */
class BomBarang extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bom_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_produksi_id', 'total_bahan_baku', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['barang_produksi_id', 'total_bahan_baku'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['barang_produksi_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barangproduksi::class, 'targetAttribute' => ['barang_produksi_id' => 'barang_produksi_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BOM_barang_id' => 'Bom Barang ID',
            'barang_produksi_id' => 'Barang Produksi ID',
            'total_bahan_baku' => 'Total Bahan Baku',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BarangProduksi]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getBomDetails()
    {
        return $this->hasMany(BomDetail::class, ['BOM_barang_id' => 'BOM_barang_id']);
    }

    public function getBarangProduksi()
    {
        return $this->hasOne(Barangproduksi::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }

}
