<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembelian".
 *
 * @property int $pembelian_id
 * @property int $pemesanan_id
 * @property int $user_id
 * @property float $total_biaya
 *
 * @property PembelianDetail[] $pembelianDetails
 * @property User $user
 */
class Pembelian extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pembelian';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pemesanan_id', 'total_biaya'], 'required'],
            [['pemesanan_id', 'user_id'], 'integer'],
            [['total_biaya'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pembelian_id' => 'Pembelian ID',
            'pemesanan_id' => 'Pemesanan ID',
            'user_id' => 'User ID',
            'total_biaya' => 'Total Biaya',
        ];
    }

    /**
     * Gets query for [[PembelianDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class, ['pembelian_id' => 'pembelian_id']);
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

    public function getPemesanan()
    {
        return $this->hasOne(Pemesanan::class, ['pemesanan_id' => 'pemesanan_id']);
    }
}
