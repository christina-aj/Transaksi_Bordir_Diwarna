<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "permintaan_penjualan".
 *
 * @property int $permintaan_penjualan_id
 * @property int|null $total_item_permintaan
 * @property string|null $tanggal_permintaan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PemintaanDetail[] $permintaanDetails
 */
class PermintaanPenjualan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public $kode_permintaan;

    
    public static function tableName()
    {
        return 'permintaan_penjualan';
    }

    public function rules()
    {
        return [
            [['total_item_permintaan', 'tanggal_permintaan', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['nama_pelanggan'], 'string', 'max' => 255],
            [['total_item_permintaan'], 'integer'],
            [['tanggal_permintaan', 'created_at', 'updated_at', 'kode_permintaan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'permintaan_penjualan_id' => 'Permintaan Penjualan ID',
            'kode_permintaan' => 'Kode Permintaan',
            'nama_pelanggan' => 'Nama Pelanggan',
            'total_item_permintaan' => 'Total Item Permintaan',
            'tanggal_permintaan' => 'Tanggal Permintaan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[ModelDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelDetails()
    {
        return $this->hasMany(PermintaanDetail::class, ['permintaan_penjualan_id' => 'permintaan_penjualan_id']);
    }

    public function getPermintaanDetails()
    {
        return $this->hasMany(PermintaanDetail::class, ['permintaan_penjualan_id' => 'permintaan_penjualan_id']);
    }

    public function getFormattedPermintaanId()
    {
        if ($this->permintaan_penjualan_id === null) {
            return null;
        }
        return 'PP-' . str_pad($this->permintaan_penjualan_id, 3, '0', STR_PAD_LEFT);
    }

}
