<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "pembelian_detail".
 *
 * @property int $belidetail_id
 * @property int $pembelian_id
 * @property int $barang_id
 * @property float $harga_barang
 * @property float $quantity_barang
 * @property float $total_biaya
 * @property string|null $catatan
 * @property int $langsung_pakai
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barang $barang
 * @property Pembelian $pembelian
 */
class PembelianDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pembelian_detail';
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
            [['pembelian_id', 'barang_id', 'harga_barang', 'quantity_barang', 'total_biaya', 'langsung_pakai'], 'required'],
            [['pembelian_id', 'barang_id', 'langsung_pakai'], 'integer'],
            [['harga_barang', 'quantity_barang', 'total_biaya'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'belidetail_id' => 'Belidetail ID',
            'pembelian_id' => 'Pembelian ID',
            'barang_id' => 'Barang ID',
            'harga_barang' => 'Harga Barang',
            'quantity_barang' => 'Quantity Barang',
            'total_biaya' => 'Total Biaya',
            'catatan' => 'Catatan',
            'langsung_pakai' => 'Langsung Pakai',
            'created_at' => 'Dibuat Pada',
            'updated_at' => 'Diperbarui Pada',
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
     * Gets query for [[Pembelian]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelian()
    {
        return $this->hasOne(Pembelian::class, ['pembelian_id' => 'pembelian_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::debug('afterSave() called for PembelianDetail ID: ' . $this->belidetail_id, __METHOD__);
        parent::afterSave($insert, $changedAttributes);
        $this->updateStock();
        $this->updatePembelianTotalBiaya();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        $this->updatePembelianTotalBiaya();
        $this->updateStock();
    }

    protected function updatePembelianTotalBiaya()
    {
        // Hitung total biaya dari semua detail pembelian untuk pembelian_id ini
        $totalBiaya = PembelianDetail::find()
            ->where(['pembelian_id' => $this->pembelian_id])
            ->sum('total_biaya');

        // Update total_biaya di tabel pembelian
        Pembelian::updateAll(['total_biaya' => $totalBiaya], ['pembelian_id' => $this->pembelian_id]);
    }

    protected function updateStock()
    {
        // Cek apakah barang_id ada di stok
        $stock = Stock::findOne(['barang_id' => $this->barang_id]);

        if ($stock) {
            if ($this->langsung_pakai == 1) {
                $stock->quantity_keluar += $this->quantity_barang;
            } else {
                $stock->quantity_masuk += $this->quantity_barang;
                $stock->quantity_akhir += $this->quantity_barang;
            }
        } else {
            // Jika barang belum ada di stok, buat entri stok baru
            $stock = new Stock();
            $stock->barang_id = $this->barang_id;
            if ($this->langsung_pakai == 1) {
                $stock->quantity_awal = 0;
                $stock->quantity_masuk = 0;
                $stock->quantity_keluar = $this->quantity_barang;
                $stock->quantity_akhir = 0;
            } else {
                $stock->quantity_awal = 0;
                $stock->quantity_masuk = $this->quantity_barang;
                $stock->quantity_keluar = 0;
                $stock->quantity_akhir = $this->quantity_barang;
            }
        }

        if ($stock->save()) {
            Yii::debug("Stock updated for barang_id: {$this->barang_id}");
        } else {
            Yii::error("Failed to update stock for barang_id: {$this->barang_id}. Errors: " . json_encode($stock->getErrors()));
        }
    }
}
