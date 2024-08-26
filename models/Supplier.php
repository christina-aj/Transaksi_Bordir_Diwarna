<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $supplier_id
 * @property string $nama
 * @property string $notelfon
 * @property string $alamat
 * @property string $kota
 * @property int $kodepos
 *
 * @property Item[] $items
 * @property Pembelian[] $pembelians
 */
class Supplier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'notelfon', 'alamat', 'kota', 'kodepos'], 'required'],
            [['kodepos'], 'integer'],
            [['nama', 'notelfon', 'alamat', 'kota'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'supplier_id' => 'Supplier ID',
            'nama' => 'Nama',
            'notelfon' => 'Notelfon',
            'alamat' => 'Alamat',
            'kota' => 'Kota',
            'kodepos' => 'Kodepos',
        ];
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(Item::class, ['supplier_id' => 'supplier_id']);
    }

    /**
     * Gets query for [[Pembelians]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelians()
    {
        return $this->hasMany(Pembelian::class, ['supplier_id' => 'supplier_id']);
    }
}
