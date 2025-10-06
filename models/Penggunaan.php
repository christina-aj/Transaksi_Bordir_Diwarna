<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "penggunaan".
 *
 * @property int $penggunaan_id
 * @property int $user_id
 * @property int $total_item_penggunaan
 * @property int $status_penggunaan
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string $tanggal
 *
 * @property PenggunaanDetail[] $penggunaanDetails
 * @property User $user
 */
class Penggunaan extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */

    public $kode_penggunaan;
    public $nama_pengguna;

    const STATUS_PENDING = 0;
    const STATUS_COMPLETE = 1;

    public static function tableName()
    {
        return 'penggunaan';
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
        return $statusLabels[$this->status_penggunaan] ?? 'Unknown';
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
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'default', 'value' => null],
            [['status_penggunaan'], 'default', 'value' => 0],
            [['user_id', 'total_item_penggunaan', 'tanggal'], 'required'],
            [['user_id', 'total_item_penggunaan', 'status_penggunaan'], 'integer'],
            [['created_at', 'updated_at', 'tanggal', 'kode_penggunaan', 'nama_pengguna'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'penggunaan_id' => 'Penggunaan ID',
            'kode_penggunaan' => 'Kode Penggunaan',
            'nama_pengguna' => 'Nama Pengguna',

            'user_id' => 'User ID',
            'total_item_penggunaan' => 'Total Item Penggunaan',
            // 'status_penggunaan' => 'Status Penggunaan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'tanggal' => 'Tanggal',
        ];
    }

    /**
     * Gets query for [[PenggunaanDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPenggunaanDetails()
    {
        return $this->hasMany(PenggunaanDetail::class, ['penggunaan_id' => 'penggunaan_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }
    public function getFormattedGunaId()
    {
        if ($this->penggunaan_id === null) {
            return null;
        }
        return 'PG-' . str_pad($this->penggunaan_id, 3, '0', STR_PAD_LEFT);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Mengubah format tanggal dari dd-mm-yyyy ke yyyy-mm-dd sebelum disimpan
            $this->tanggal = Yii::$app->formatter->asDate($this->tanggal, 'php:Y-m-d');
            return true;
        } else {
            return false;
        }
    }
    public function updateTotalItem($penggunaan_id)
    {
        // Menghitung total item dari semua detail pembelian menggunakan relasi
        $totalItem = $this->getPenggunaanDetails()
            ->where(['penggunaan_id' => $penggunaan_id])
            ->count();

        // Update total item pada tabel pembelian
        $this->total_item_penggunaan = $totalItem;
        return $this->save();
    }

}
