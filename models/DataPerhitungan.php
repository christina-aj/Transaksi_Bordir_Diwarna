<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_perhitungan".
 *
 * @property int $data_perhitungan_id
 * @property int $barang_id
 * @property float $biaya_pesan
 * @property float $biaya_simpan
 * @property float $safety_stock
 * @property int $lead_time_rerata
 * @property string $periode_mulasi
 * @property string|null $periode_selesai
 * @property string|null $created_at
 *
 * @property EoqRop[] $eoqRops
 */
class DataPerhitungan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'data_perhitungan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periode_selesai', 'created_at'], 'default', 'value' => null],
            [['lead_time_rerata'], 'default', 'value' => 0],
            [['barang_id', 'biaya_pesan', 'biaya_simpan', 'safety_stock', 'periode_mulasi'], 'required'],
            [['barang_id', 'lead_time_rerata'], 'integer'],
            [['biaya_pesan', 'biaya_simpan', 'safety_stock'], 'number'],
            [['periode_mulasi', 'periode_selesai', 'created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'data_perhitungan_id' => 'Data Perhitungan ID',
            'barang_id' => 'Barang ID',
            'biaya_pesan' => 'Biaya Pesan',
            'biaya_simpan' => 'Biaya Simpan',
            'safety_stock' => 'Safety Stock',
            'lead_time_rerata' => 'Lead Time Rerata',
            'periode_mulasi' => 'Periode Mulasi',
            'periode_selesai' => 'Periode Selesai',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[EoqRops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEoqRops()
    {
        return $this->hasMany(EoqRop::class, ['data_perhitungan_id' => 'data_perhitungan_id']);
    }

}
