<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier_barang".
 *
 * @property int $supplier_barang_id
 * @property int $barang_id
 * @property int $total_supplier_barang
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property Barang $barang
 * @property Supplier $supplierBarang
 * @property SupplierBarangDetail[] $supplierBarangDetails
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
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['barang_id', 'total_supplier_barang'], 'required'],
            [['barang_id', 'total_supplier_barang', 'created_at', 'updated_at'], 'integer'],
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
            'total_supplier_barang' => 'Total Supplier Barang',
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
     * Gets query for [[SupplierBarang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBarang()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_barang_id']);
    }

    /**
     * Gets query for [[SupplierBarangDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplierBarangDetails()
    {
        return $this->hasMany(SupplierBarangDetail::class, ['supplier_barang_id' => 'supplier_barang_id']);
    }

}
