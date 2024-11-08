<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembelian_detail".
 *
 * @property int $belidetail_id
 * @property int $pembelian_id
 * @property int $pesandetail_id
 * @property float $cek_barang
 * @property float $total_biaya
 * @property int $supplier_id
 * @property string|null $catatan
 * @property int $is_correct
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Pembelian $pembelian
 * @property PesanDetail $pesandetail
 */
class PembelianDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $nama_supplier;
    public static function tableName()
    {
        return 'pembelian_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pembelian_id', 'pesandetail_id', 'cek_barang', 'total_biaya', 'is_correct', 'supplier_id'], 'required'],
            [['pembelian_id', 'pesandetail_id', 'is_correct', 'supplier_id'], 'integer'],
            [['cek_barang', 'total_biaya'], 'number'],
            [['created_at', 'updated_at', 'nama_supplier'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['pembelian_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pembelian::class, 'targetAttribute' => ['pembelian_id' => 'pembelian_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'belidetail_id' => 'Belidetail ID',
            'pembelian_id' => 'Pembelian ID',
            'nama_supplier' => 'Nama Supplier',
            'pesandetail_id' => 'Pesandetail ID',
            'cek_barang' => 'Cek Barang',
            'total_biaya' => 'Total Biaya',
            'supplier_id' => 'Supplier Id',
            'catatan' => 'Catatan',
            'is_correct' => 'Is Correct',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Pembelian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelian()
    {
        return $this->hasOne(Pembelian::class, ['pembelian_id' => 'pembelian_id']);
    }

    public function getPesanDetail()
    {
        return $this->hasOne(PesanDetail::class, ['pesandetail_id' => 'pesandetail_id']);
    }
    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
    }
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_id']);
    }
}
