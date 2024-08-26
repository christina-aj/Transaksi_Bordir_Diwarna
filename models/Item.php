<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $kategori
 * @property int $supplier_id
 * @property int $unit_id
 * @property int $jumlah
 * @property string $harga
 * @property string $total
 * @property string $tempat_belanja
 *
 * @property Supplier $supplier
 * @property Unit $unit
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kategori', 'supplier_id', 'unit_id', 'jumlah', 'harga', 'total', 'tempat_belanja'], 'required'],
            [['kategori'], 'string'],
            [['supplier_id', 'unit_id', 'jumlah'], 'integer'],
            [['harga', 'total', 'tempat_belanja'], 'string', 'max' => 200],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'supplier_id']],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'unit_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kategori' => 'Kategori',
            'supplier_id' => 'Supplier ID',
            'unit_id' => 'Unit ID',
            'jumlah' => 'Jumlah',
            'harga' => 'Harga',
            'total' => 'Total',
            'tempat_belanja' => 'Tempat Belanja',
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
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['unit_id' => 'unit_id']);
    }
}
