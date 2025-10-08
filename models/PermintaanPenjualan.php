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
 * @property int|null $status_permintaan 0 = pending, 1=sukses/terpenuhi
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property DetailPermintaan[] $detailPermintaans
 */
class PermintaanPenjualan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public $kode_permintaan;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETE = 1;
    
    public static function tableName()
    {
        return 'permintaan_penjualan';
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => '<span style="color: orange">Pending</span>',
            self::STATUS_COMPLETE => '<span style="color: green">Complete</span>',
        ];
    }

    // Metode untuk mendapatkan label status berdasarkan nilai
    public function getStatusLabel()
    {
        $statusLabels = self::getStatusLabels();
        return $statusLabels[$this->status_permintaan] ?? 'Unknown';
    }

    public function rules()
    {
        return [
            [['total_item_permintaan', 'tanggal_permintaan', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['status_permintaan'], 'default', 'value' => 0],
            [['total_item_permintaan', 'status_permintaan'], 'integer'],
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
            'total_item_permintaan' => 'Total Item Permintaan',
            'tanggal_permintaan' => 'Tanggal Permintaan',
            // 'status_permintaan' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[DetailPermintaans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDetailPermintaans()
    {
        return $this->hasMany(DetailPermintaan::class, ['permintaan_penjualan_id' => 'permintaan_penjualan_id']);
    }

    public function getFormattedPermintaanId()
    {
        if ($this->permintaan_penjualan_id === null) {
            return null;
        }
        return 'PP-' . str_pad($this->permintaan_penjualan_id, 3, '0', STR_PAD_LEFT);
    }

}
