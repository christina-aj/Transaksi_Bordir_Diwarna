<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bom_custom".
 *
 * @property int $BOM_custom_id
 * @property int $barang_custom_pelanggan_id
 * @property int $barang_id
 * @property int $qty_per_unit
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barang $barang
 * @property BarangCustomPelanggan $barangCustomPelanggan
 */
class BomCustom extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bom_custom';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['barang_custom_pelanggan_id', 'barang_id', 'qty_per_unit'], 'required'],
            [['barang_custom_pelanggan_id', 'barang_id', 'qty_per_unit'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['barang_custom_pelanggan_id'], 'exist', 'skipOnError' => true, 'targetClass' => BarangCustomPelanggan::class, 'targetAttribute' => ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'BOM_custom_id' => 'Bom Custom ID',
            'barang_custom_pelanggan_id' => 'Barang Custom Pelanggan ID',
            'barang_id' => 'Barang ID',
            'qty_per_unit' => 'Qty Per Unit',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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

    /**
     * Gets query for [[BarangCustomPelanggan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangCustomPelanggan()
    {
        return $this->hasOne(BarangCustomPelanggan::class, ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']);
    }

}
