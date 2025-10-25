<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "permintaan_pelanggan".
 *
 * @property int $permintaan_id
 * @property int|null $pelanggan_id
 * @property int $tipe_pelanggan
 * @property int|null $total_item_permintaan
 * @property string|null $tanggal_permintaan
 * @property int|null $status_permintaan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property MasterPelanggan $pelanggan
 * @property PermintaanDetail[] $permintaanDetails
 */
class PermintaanPelanggan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $kode_penggunaan;
    public $nama_pengguna;

    const STATUS_PENDING = 0;
    const STATUS_ON_PROGRESS = 1;
    const STATUS_COMPLETE = 2;

    const KODE_CUSTOM = 1;
    const KODE_READY = 2;

    public static function tableName()
    {
        return 'permintaan_pelanggan';
    }

    /**
     * {@inheritdoc}
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_PENDING => '<span style="color: orange">Pending</span>',
            self::STATUS_ON_PROGRESS => '<span style="color: blue">On Progress</span>',
            self::STATUS_COMPLETE => '<span style="color: green">Complete</span>',
        ];
    }
    public function getStatusLabel()
    {
        $statusLabels = self::getStatusLabels();
        return $statusLabels[$this->status_permintaan] ?? 'Unknown';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'), // or date('Y-m-d H:i:s')
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pelanggan_id', 'tipe_pelanggan', 'tanggal_permintaan'], 'required'],
            [['pelanggan_id', 'tipe_pelanggan', 'total_item_permintaan'], 'integer'],
            [['tanggal_permintaan', 'tanggal_waktu', 'created_at', 'updated_at'], 'safe'],
            [['kode_permintaan'], 'string', 'max' => 50],
            [['status_permintaan'], 'default', 'value' => 0],
            // [['status_permintaan'], 'string', 'max' => 20],
            [['tipe_pelanggan'], 'in', 'range' => [1, 2]], // 1=custom, 2=polosan
            [['pelanggan_id'], 'exist', 'skipOnError' => true, 'targetClass' => MasterPelanggan::class, 'targetAttribute' => ['pelanggan_id' => 'pelanggan_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'permintaan_id' => 'Permintaan ID',
            'kode_permintaan' => 'Kode Permintaan',
            'nama_pelanggan' => 'Nama Pelanggan',
            'pelanggan_id' => 'Pelanggan ID',
            'tipe_pelanggan' => 'Tipe Pelanggan',
            'total_item_permintaan' => 'Total Item',
            'tanggal_permintaan' => 'Tanggal Permintaan',
            'tanggal_waktu' => 'Tanggal Waktu',
            'status_permintaan' => 'Status Permintaan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
        return $this->hasMany(PermintaanDetail::class, ['permintaan_id' => 'permintaan_id']);
    }

    /**
     * Get tipe pelanggan label
     */
    public function getTipePelangganLabel()
    {
        return $this->tipe_pelanggan == 1 ? 'Custom' : 'Polosan Ready';
    }

    /**
     * Before save
     */
    // public function beforeSave($insert)
    // {
    //     if (parent::beforeSave($insert)) {
    //         if ($insert) {
    //             $this->created_at = time();
    //             // Auto generate kode_permintaan jika kosong
    //             if (empty($this->kode_permintaan)) {
    //                 $this->kode_permintaan = $this->generateKodePermintaan();
    //             }
    //         }
    //         $this->updated_at = time();
    //         return true;
    //     }
    //     return false;
    // }

    public function generateKodePermintaan()
    {
        if ($this->permintaan_id === null) {
            return null;
        }
        return 'PP-' . str_pad($this->permintaan_id, 3, '0', STR_PAD_LEFT);
    }

    public function getTipePelangganLabelButton()
    {
        $labels = [
            self::KODE_CUSTOM => 'Custom Order',
            self::KODE_READY => 'Ready Stock Order',
        ];
        return isset($labels[$this->jenis_barang]) ? $labels[$this->jenis_barang] : 'Unknown';
    }
}