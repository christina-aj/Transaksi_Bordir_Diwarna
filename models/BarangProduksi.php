<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "barangproduksi".
 *
 * @property int $barang_id
 * @property string $nama
 * @property string $jenis
 * @property string $deskripsi
 */
class Barangproduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barangproduksi';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'nama_jenis', 'deskripsi'], 'required'],
            [['deskripsi'], 'string'],
            [['nama', 'nama_jenis'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang ID',
            'nama' => 'Nama Barang',
            'nama_jenis' => 'Jenis',
            'deskripsi' => 'Deskripsi',
        ];
    }

    public function getJenis()
    {
        return $this->hasOne(Jenis::class, ['id' => 'id']);
    }
}
