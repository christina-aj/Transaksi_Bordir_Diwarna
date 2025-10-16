<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier_barang_detail".
 *
 * @property int $supplier_barang_detail_id
 * @property int $supplier_barang_id
 * @property int $supplier_id
 * @property float $lead_time
 * @property float $harga_per_kg
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Supplier $supplier
 * @property SupplierBarang $supplierBarang
 */
class SupplierBarangDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier_barang_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['harga_per_kg'], 'default', 'value' => 0],
            [['supplier_barang_id', 'supplier_id', 'lead_time'], 'required'],
            [['supplier_barang_id', 'supplier_id'], 'integer'],
            [['lead_time', 'harga_per_kg'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'supplier_id']],
            [['supplier_barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupplierBarang::class, 'targetAttribute' => ['supplier_barang_id' => 'supplier_barang_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'supplier_barang_detail_id' => 'Supplier Barang Detail ID',
            'supplier_barang_id' => 'Supplier Barang ID',
            'supplier_id' => 'Supplier ID',
            'lead_time' => 'Lead Time',
            'harga_per_kg' => 'Harga Per Kg',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_id']);
    }

    /**
     * Gets query for [[SupplierBarang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBarang()
    {
        return $this->hasOne(SupplierBarang::class, ['supplier_barang_id' => 'supplier_barang_id']);
    }

}
