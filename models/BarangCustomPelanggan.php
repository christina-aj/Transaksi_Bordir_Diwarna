<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "barang_custom_pelanggan".
 *
 * @property int $barang_custom_pelanggan_id
 * @property int $pelanggan_id
 * @property string $kode_barang_custom
 * @property string $nama_barang_custom
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property BomCustom[] $bomCustoms
 * @property MasterPelanggan $pelanggan
 * @property PermintaanDetail[] $permintaanDetails
 */
class BarangCustomPelanggan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang_custom_pelanggan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['nama_barang_custom'], 'default', 'value' => ''],
            [['pelanggan_id'], 'required'],
            [['pelanggan_id', 'created_at', 'updated_at'], 'integer'],
            [['kode_barang_custom', 'nama_barang_custom'], 'string', 'max' => 255],
            [['pelanggan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPelanggan::class, 'targetAttribute' => ['pelanggan_id' => 'pelanggan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_custom_pelanggan_id' => 'Barang Custom Pelanggan ID',
            'pelanggan_id' => 'Pelanggan ID',
            'kode_barang_custom' => 'Kode Barang Custom',
            'nama_barang_custom' => 'Nama Barang Custom',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BomCustoms]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBomCustoms()
    {
        return $this->hasMany(BomCustom::class, ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']);
    }

    /**
     * Gets query for [[Pelanggan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPelanggan()
    {
        return $this->hasOne(MasterPelanggan::class, ['pelanggan_id' => 'pelanggan_id']);
    }

    /**
     * Gets query for [[PermintaanDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermintaanDetails()
    {
        return $this->hasMany(PermintaanDetail::class, ['barang_custom_pelanggan_id' => 'barang_custom_pelanggan_id']);
    }

}
