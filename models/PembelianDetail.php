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
 * @property string|null $catatan
 * @property int $is_correct
 * @property string|null $created_at
 * @property string|null $updated_at
 *
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
            [['pembelian_id', 'pesandetail_id', 'cek_barang', 'total_biaya', 'is_correct'], 'required'],
            [['pembelian_id', 'pesandetail_id', 'is_correct'], 'integer'],
            [['cek_barang', 'total_biaya'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
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
            'pesandetail_id' => 'Pesandetail ID',
            'cek_barang' => 'Cek Barang',
            'total_biaya' => 'Total Biaya',
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
}
