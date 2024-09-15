<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "gudang".
 *
 * @property int $id_gudang
 * @property string $tanggal
 * @property int $barang_id
 * @property int $user_id
 * @property float $quantity_awal
 * @property float $quantity_masuk
 * @property float $quantity_keluar
 * @property float $quantity_akhir
 * @property string $catatan
 * @property string|null $created_at
 * @property string|null $update_at
 *
 * @property Barang $barang
 * @property User $user
 */
class Gudang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gudang';
    }

    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'update_at'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['update_at'],
                ],
                'value' => new Expression('NOW()'), // or date('Y-m-d H:i:s')
            ],
        ];
    }
    public function rules()
    {
        return [
            [['tanggal', 'barang_id', 'user_id', 'quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir'], 'required'],
            [['tanggal', 'created_at', 'update_at'], 'safe'],
            [['barang_id', 'user_id'], 'integer'],
            [['quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir'], 'number'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_gudang' => 'Id Gudang',
            'tanggal' => 'Tanggal',
            'barang_id' => 'Barang ID',
            'user_id' => 'User ID',
            'quantity_awal' => 'Quantity Awal',
            'quantity_masuk' => 'Quantity Masuk',
            'quantity_keluar' => 'Quantity Keluar',
            'quantity_akhir' => 'Quantity Akhir',
            'catatan' => 'Catatan',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }

    /**
     * Gets query for [[Barang]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
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
}
