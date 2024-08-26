<?php

namespace app\models;

use Yii;

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
 * @property PembelianDetail $barang
 * @property Item $item
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
    public function rules()
    {
        return [
            [['kode_barang', 'nama_barang', 'unit_id', 'harga', 'tipe'], 'required'],
            [['unit_id'], 'integer'],
            [['harga'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_barang', 'nama_barang', 'tipe', 'warna'], 'string', 'max' => 255],
            [['unit_id'], 'unique'],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => PembelianDetail::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
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
     * Gets query for [[Barang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(PembelianDetail::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class, ['unit_id' => 'unit_id']);
    }
}
