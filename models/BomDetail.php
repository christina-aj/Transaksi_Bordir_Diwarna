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
 * @property BomBarang $bOMBarang
 * @property Barang $barang
 */
class BomDetail extends \yii\db\ActiveRecord
{


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
            [['barang_id', 'qty_BOM', 'catatan'], 'default', 'value' => null],
            [['BOM_barang_id'], 'default', 'value' => 0],
            [['BOM_barang_id', 'barang_id', 'qty_BOM'], 'integer'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['BOM_barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => BomBarang::class, 'targetAttribute' => ['BOM_barang_id' => 'BOM_barang_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BOM_detail_id' => 'Bom Detail ID',
            'BOM_barang_id' => 'Bom Barang ID',
            'barang_id' => 'Barang ID',
            'qty_BOM' => 'Qty Bom',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Gets query for [[BOMBarang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBomBarang()
    {
        return $this->hasOne(BomBarang::class, ['BOM_barang_id' => 'BOM_barang_id']);
    }

    public function getBarangProduksi()
    {
        // Lewat tabel bom_barang
        return $this->hasOne(\app\models\BarangProduksi::class, ['barang_produksi_id' => 'barang_produksi_id'])
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

}
