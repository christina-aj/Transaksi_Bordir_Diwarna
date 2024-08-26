<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembelian_detail".
 *
 * @property int $belidetail_id
 * @property int $pembelian_id
 * @property int $barang_id
 * @property float $harga_barang
 * @property float $quantity_barang
 * @property float $total_biaya
 * @property string|null $catatan
 * @property int $langsung_pakai
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barang $barang
 * @property Stock $barang0
 * @property Pembelian $pembelian
 */
class PembelianDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
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
            [['pembelian_id', 'barang_id', 'harga_barang', 'quantity_barang', 'total_biaya', 'langsung_pakai'], 'required'],
            [['pembelian_id', 'barang_id', 'langsung_pakai'], 'integer'],
            [['harga_barang', 'quantity_barang', 'total_biaya'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['pembelian_id'], 'unique'],
            [['barang_id'], 'unique'],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
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
            'barang_id' => 'Barang ID',
            'harga_barang' => 'Harga Barang',
            'quantity_barang' => 'Quantity Barang',
            'total_biaya' => 'Total Biaya',
            'catatan' => 'Catatan',
            'langsung_pakai' => 'Langsung Pakai',
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
     * Gets query for [[Barang0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarang0()
    {
        return $this->hasOne(Stock::class, ['barang_id' => 'barang_id']);
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
}
