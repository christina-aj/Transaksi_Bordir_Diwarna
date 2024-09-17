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
 * @property int $supplier_id
 * @property float $harga
 * @property float $angka
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
            [['unit_id', 'supplier_id'], 'integer'],
            [['harga', 'angka'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_barang', 'nama_barang', 'tipe', 'warna'], 'string', 'max' => 255],
            [['kode_barang'], 'unique'],
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
            'angka' => 'Angka',
            'unit_id' => 'Satuan',
            'harga' => 'Harga',
            'tipe' => 'Tipe',
            'warna' => 'Warna',
            'supplier_id' => 'Supplier Id',
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

    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_id']);
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
