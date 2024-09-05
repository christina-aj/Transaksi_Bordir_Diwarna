<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembelian".
 *
 * @property int $pembelian_id
 * @property int $user_id
 * @property string $tanggal
 * @property int $supplier_id
 * @property string $total_biaya
 * @property int $langsung_pakai
 * @property string $kode_struk
 *
 * @property PembelianDetail $pembelian
 * @property Supplier $supplier
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
            [['user_id', 'tanggal', 'supplier_id', 'total_biaya', 'langsung_pakai', 'kode_struk'], 'required'],
            [['user_id', 'supplier_id', 'langsung_pakai'], 'integer'],
            [['tanggal'], 'safe'],
            [['total_biaya'], 'string', 'max' => 200],
            [['kode_struk'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => Supplier::class, 'targetAttribute' => ['supplier_id' => 'supplier_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pembelian_id' => 'Pembelian ID',
            'user_id' => 'User',
            'tanggal' => 'Tanggal',
            'supplier_id' => 'Supplier',
            'total_biaya' => 'Total Biaya',
            'langsung_pakai' => 'Langsung Pakai',
            'kode_struk' => 'Kode Struk',
        ];
    }

    /**
     * Gets query for [[Pembelian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelian()
    {
        return $this->hasOne(PembelianDetail::class, ['pembelian_id' => 'pembelian_id']);
    }

    /**
     * Gets query for [[Supplier]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(Supplier::class, ['supplier_id' => 'supplier_id']);
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
}
