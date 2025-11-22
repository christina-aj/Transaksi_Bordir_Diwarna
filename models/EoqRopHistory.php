<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eoq_rop_history".
 *
 * @property int $eoq_rop_history_id
 * @property int $barang_id
 * @property float|null $biaya_pesan_snapshot
 * @property float|null $biaya_simpan_snapshot
 * @property float|null $safety_stock_snapshot
 * @property int|null $lead_time_snapshot
 * @property float|null $demand_snapshot
 * @property float|null $total_biaya_persediaan
 * @property float|null $hasil_eoq
 * @property float|null $hasil_rop
 * @property string $periode
 * @property string|null $created_at
 */
class EoqRopHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eoq_rop_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lead_time_snapshot', 'hasil_eoq', 'hasil_rop', 'created_at'], 'default', 'value' => null],
            [['total_biaya_persediaan'], 'default', 'value' => 0],
            [['barang_id', 'periode'], 'required'],
            [['barang_id', 'lead_time_snapshot'], 'integer'],
            [['biaya_pesan_snapshot', 'biaya_simpan_snapshot', 'safety_stock_snapshot', 'demand_snapshot', 'total_biaya_persediaan', 'hasil_eoq', 'hasil_rop'], 'number'],
            [['created_at'], 'safe'],
            [['periode'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'eoq_rop_history_id' => 'Eoq Rop History ID',
            'barang_id' => 'Barang ID',
            'biaya_pesan_snapshot' => 'Biaya Pesan Snapshot',
            'biaya_simpan_snapshot' => 'Biaya Simpan Snapshot',
            'safety_stock_snapshot' => 'Safety Stock Snapshot',
            'lead_time_snapshot' => 'Lead Time Snapshot',
            'demand_snapshot' => 'Demand Snapshot',
            'total_biaya_persediaan' => 'Total Biaya Perediaan',
            'hasil_eoq' => 'Hasil Eoq',
            'hasil_rop' => 'Hasil Rop',
            'periode' => 'Periode',
            'created_at' => 'Created At',
        ];
    }

}
