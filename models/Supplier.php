<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $nama
 * @property int $notelfon
 * @property string $alamat
 * @property string $kota
 * @property int $kodepos
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
            [['notelfon', 'kodepos'], 'integer'],
            [['nama', 'alamat', 'kota'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama' => 'Nama',
            'notelfon' => 'Notelfon',
            'alamat' => 'Alamat',
            'kota' => 'Kota',
            'kodepos' => 'Kodepos',
        ];
    }
}
