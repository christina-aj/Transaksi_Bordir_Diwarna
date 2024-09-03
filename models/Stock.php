<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "stock".
 *
 * @property int $stock_id
 * @property string $tambah_stock
 * @property int $barang_id
 * @property float $quantity_awal
 * @property float $quantity_masuk
 * @property float $quantity_keluar
 * @property float $quantity_akhir
 * @property int $user_id
 * @property int $is_ready
 * @property int $is_new
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PembelianDetail $pembelianDetail
 * @property User $user
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
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
            [['tambah_stock', 'barang_id', 'quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir', 'user_id', 'is_ready', 'is_new'], 'required'],
            [['tambah_stock', 'created_at', 'updated_at'], 'safe'],
            [['barang_id', 'user_id', 'is_ready', 'is_new'], 'integer'],
            [['quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'stock_id' => 'Stock ID',
            'tambah_stock' => 'Tambah Stock',
            'barang_id' => 'Barang ID',
            'quantity_awal' => 'Quantity Awal',
            'quantity_masuk' => 'Quantity Masuk',
            'quantity_keluar' => 'Quantity Keluar',
            'quantity_akhir' => 'Quantity Akhir',
            'user_id' => 'User ID',
            'is_ready' => 'Is Ready',
            'is_new' => 'Is New',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PembelianDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelianDetail()
    {
        return $this->hasOne(PembelianDetail::class, ['barang_id' => 'barang_id']);
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

    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
    }
}
