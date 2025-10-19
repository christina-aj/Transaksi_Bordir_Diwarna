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
    
    public $nama_barang; // untuk keperluan view/update
    public $kode_barang; // untuk keperluan view/update

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
            [['harga_per_kg', 'supp_utama'], 'default', 'value' => 0],
            [['supplier_barang_id', 'supplier_id', 'lead_time'], 'required'],
            [['supplier_barang_id', 'supplier_id'], 'integer'],
            [['lead_time', 'harga_per_kg'], 'number'],
            [['supp_utama'], 'boolean'],
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
            'supp_utama' => 'Supplier Utama',
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

        /**
     * Gets query for [[Barang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id'])
        ->via('supplierBarang');
    }

    
    /**
     * Get nama barang (bahan baku)
     */
    public function getNamaBarang()
    {
        return $this->barang ? $this->barang->nama_barang : '-';
    }

    /**
     * Get kode barang (bahan baku)
     */
    public function getKodeBarang()
    {
        return $this->barang ? $this->barang->kode_barang : '-';
    }

}
