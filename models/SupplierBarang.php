<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier_barang".
 *
 * @property int $supplier_barang_id
 * @property int|null $barang_id
 * @property int|null $supplier_id
 * @property float|null $lead_time
 * @property float|null $harga_per_kg
 * @property int|null $created_at
 *
 * @property Barang $barang
 * @property Supplier $supplierBarang
 */
class SupplierBarang extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_barang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_id', 'supplier_id', 'lead_time', 'harga_per_kg', 'created_at'], 'default', 'value' => null],
            [['barang_id', 'supplier_id', 'created_at'], 'integer'],
            [['lead_time', 'harga_per_kg'], 'number'],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['supplier_barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_barang_id' => 'supplier_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'supplier_barang_id' => 'Supplier Barang ID',
            'barang_id' => 'Barang ID',
            'supplier_id' => 'Supplier ID',
            'lead_time' => 'Lead Time',
            'harga_per_kg' => 'Harga Per Kg',
            'created_at' => 'Created At',
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
     * Gets query for [[SupplierBarang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBarang()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_barang_id']);
    }

}
