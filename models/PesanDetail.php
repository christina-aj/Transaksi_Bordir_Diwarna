<?php

namespace app\models;

use yii\db\Expression;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "pesan_detail".
 *
 * @property int $pesandetail_id
 * @property int $pemesanan_id
 * @property int $barang_id
 * @property float $qty
 * @property float|null $qty_terima
 * @property string|null $catatan
 * @property int $is_correct
 * @property int $langsung_pakai
 * @property string $created_at
 * @property string $update_at
 *
 * @property Barang $barang
 * @property Pemesanan $pemesanan
 */
class PesanDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $nama_barang;
    public $kode_pemesanan;
    public static function tableName()
    {
        return 'pesan_detail';
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
            [['pemesanan_id', 'barang_id', 'qty', 'is_correct', 'langsung_pakai', 'nama_barang', 'kode_pemesanan'], 'required'],
            [['pemesanan_id', 'barang_id', 'is_correct', 'langsung_pakai'], 'integer'],
            [['qty', 'qty_terima'], 'number'],
            [['qty_terima'], 'default', 'value' => 0],
            [['is_correct'], 'default', 'value' => 0],
            [['langsung_pakai'], 'default', 'value' => 0],
            [['created_at', 'update_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['pemesanan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pemesanan::class, 'targetAttribute' => ['pemesanan_id' => 'pemesanan_id']],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kode_pemesanan' => 'Kode Pemesanan',
            'nama_barang' => 'Nama Barang',
            'pesandetail_id' => 'Pesandetail ID',
            'pemesanan_id' => 'Pemesanan ID',
            'barang_id' => 'Barang ID',
            'qty' => 'Qty',
            'qty_terima' => 'Qty Terima',
            'langsung_pakai' => 'Langsung Pakai',
            'catatan' => 'Catatan',
            'is_correct' => 'Is Correct',
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
     * Gets query for [[Pemesanan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPemesanan()
    {
        return $this->hasOne(Pemesanan::class, ['pemesanan_id' => 'pemesanan_id']);
    }
    public function getFormattedOrderId()
    {
        return 'FPB-' . str_pad((string) $this->pemesanan_id, 3, '0', STR_PAD_LEFT);
    }

    public function getFormattedOrderIdProperty($pemesanan_id)
    {
        return 'FPB-' . str_pad((string) $pemesanan_id, 3, '0', STR_PAD_LEFT);
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Panggil fungsi updateTotalItem dari model Pemesanan
        $pemesanan = Pemesanan::findOne($this->pemesanan_id);
        if ($pemesanan) {
            $pemesanan->updateTotalItem($this->pemesanan_id);
        }
    }

    // public function afterDelete()
    // {
    //     parent::afterDelete();

    //     // Panggil fungsi updateTotalItem dari model Pemesanan saat detail dihapus
    //     $pemesanan = Pemesanan::findOne($this->pemesanan_id);
    //     if ($pemesanan) {
    //         $pemesanan->updateTotalItem($this->pemesanan_id);
    //     }
    // }
}
