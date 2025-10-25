<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_pelanggan".
 *
 * @property int $pelanggan_id
 * @property string $kode_pelanggan
 * @property string $nama_pelanggan
 *
 * @property BarangCustomPelanggan[] $barangCustomPelanggans
 * @property PermintaanPelanggan[] $permintaanPelanggans
 */
class MasterPelanggan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_pelanggan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_pelanggan'], 'default', 'value' => ''],
            [['kode_pelanggan', 'nama_pelanggan'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pelanggan_id' => 'Pelanggan ID',
            'kode_pelanggan' => 'Kode Pelanggan',
            'nama_pelanggan' => 'Nama Pelanggan',
        ];
    }

    /**
     * Gets query for [[BarangCustomPelanggans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarangCustomPelanggans()
    {
        return $this->hasMany(BarangCustomPelanggan::class, ['pelanggan_id' => 'pelanggan_id']);
    }

    /**
     * Gets query for [[PermintaanPelanggans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermintaanPelanggans()
    {
        return $this->hasMany(PermintaanPelanggan::class, ['pelanggan_id' => 'pelanggan_id']);
    }

}
