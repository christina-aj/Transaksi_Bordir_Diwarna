<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "barang".
 *
 * @property int $barang_id
 * @property string $kode_barang
 * @property string $nama_barang
 * @property int $unit_id
 * @property float $harga
 * @property string $tipe
 * @property string|null $warna
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PembelianDetail[] $pembelianDetails
 * @property Stock[] $stocks
 * @property Unit $unit
 */
class Barang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang';
    }

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'), // or date('Y-m-d H:i:s')
            ],
        ];
    }
    public function rules()
    {
        return [
            [['kode_barang', 'nama_barang', 'unit_id', 'harga', 'tipe'], 'required'],
            [['unit_id'], 'integer'],
            [['harga'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_barang', 'nama_barang', 'tipe', 'warna'], 'string', 'max' => 255],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'unit_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang ID',
            'kode_barang' => 'Kode Barang',
            'nama_barang' => 'Nama Barang',
            'unit_id' => 'Unit ID',
            'harga' => 'Harga',
            'tipe' => 'Tipe',
            'warna' => 'Warna',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PembelianDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['unit_id' => 'unit_id']);
    }
}
