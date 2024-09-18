<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "pemesanan".
 *
 * @property int $pemesanan_id
 * @property int $barang_id
 * @property int $user_id
 * @property string $tanggal
 * @property float $qty
 * @property string|null $created_at
 * @property string|null $updated_at
 * 
 * @property Barang $barang
 */
class Pemesanan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pemesanan';
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
            [['barang_id', 'user_id', 'tanggal', 'qty'], 'required'],
            [['barang_id', 'user_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['qty'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pemesanan_id' => 'Pemesanan ID',
            'barang_id' => 'Barang ID',
            'user_id' => 'User ID',
            'tanggal' => 'Tanggal',
            'qty' => 'Qty',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
