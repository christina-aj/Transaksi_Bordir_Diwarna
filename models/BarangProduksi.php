<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "barangproduksi".
 *
 * @property int $barang_produksi_id
 * @property string $nama
 * @property string $nama_jenis
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
            [['kode_barang_produksi', 'nama', 'nama_jenis','ukuran'], 'required'],
            [['deskripsi'], 'string'],
            [['deskripsi'], 'default', 'value' => null],
            [['nama', 'nama_jenis'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_produksi_id' => 'Barang ID',
            'kode_barang_produksi' => 'Kode Barang Produksi',
            'nama' => 'Nama',
            'nama_jenis' => 'Jenis',
            'ukuran' => 'Ukuran',
            'deskripsi' => 'Deskripsi',
        ];
    }

    public function getBomDetails()
    {
        return $this->hasMany(BomDetail::class, ['barang_produksi_id' => 'barang_produksi_id']);
    }
    }
