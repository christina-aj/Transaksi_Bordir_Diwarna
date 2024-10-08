<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pembelian_detail".
 *
 * @property int $belidetail_id
 * @property int $pembelian_id
 * @property int $pesandetail_id
 * @property float $cek_barang
 * @property float $total_biaya
 * @property string|null $catatan
 * @property int $is_correct
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Pembelian $pembelian
 * @property PesanDetail $pesandetail
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
    public function rules()
    {
        return [
            [['pembelian_id', 'barang_id', 'harga_barang', 'quantity_barang', 'total_biaya', 'langsung_pakai'], 'required'],
            [['pembelian_id', 'barang_id', 'langsung_pakai'], 'integer'],
            [['harga_barang', 'quantity_barang', 'total_biaya'], 'number'],
            [['total_biaya'], 'default', 'value' => 0],
            [['created_at', 'updated_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['pembelian_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pembelian::class, 'targetAttribute' => ['pembelian_id' => 'pembelian_id']],
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
            'pesandetail_id' => 'Pesandetail ID',
            'cek_barang' => 'Cek Barang',
            'total_biaya' => 'Total Biaya',
            'catatan' => 'Catatan',
            'is_correct' => 'Is Correct',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
        Yii::info('Updating stock for barang_id: ' . $this->barang_id);

        // Cek apakah pembelian ini sudah ada atau tidak
        $pembelian = Pembelian::findOne($this->pembelian_id);

        if ($pembelian) {
            // Ambil user_id dan tanggal dari pembelian
            $userId = $pembelian->user_id;
            $tanggal = $pembelian->tanggal;
        } else {
            Yii::error('Pembelian not found for ID: ' . $this->pembelian_id);
            return;
        }

        // Cek apakah stock untuk barang_id ini sudah ada
        $stock = Stock::find()->where(['barang_id' => $this->barang_id])
            ->orderBy(['stock_id' => SORT_DESC]) // Mengurutkan berdasarkan ID untuk mendapatkan yang terbaru
            ->one();

        if (!$stock) {
            // Jika stock belum ada, buat entri stock baru
            Yii::info('Creating new stock entry for barang_id: ' . $this->barang_id);
            $stock = new Stock();
            $stock->barang_id = $this->barang_id;
            $stock->tambah_stock = $tanggal;
            $stock->quantity_awal = 0;
            if ($this->langsung_pakai == 1) {
                $stock->quantity_masuk = 0;
                $stock->quantity_keluar = $this->quantity_barang;
            } else {
                $stock->quantity_masuk = $this->quantity_barang;
                $stock->quantity_keluar = 0;
            }
            if ($stock->quantity_keluar == 0) {
                $stock->quantity_akhir = $stock->quantity_awal + $stock->quantity_masuk;
            } else {
                $stock->quantity_akhir = $stock->quantity_awal;
            }
            $stock->is_ready = ($stock->quantity_akhir >= 0 && $this->langsung_pakai == 1) ? 1 : 0;
            $stock->is_new = ($this->langsung_pakai == 0) ? 1 : 0;
            $stock->user_id = $userId;


            if (!$stock->save()) {
                Yii::error('Failed to save stock: ' . print_r($stock->errors, true));
            }
        } else {
            // Jika stock sudah ada, buat entri stock baru dengan nilai sebelumnya
            Yii::info('Updating existing stock entry for barang_id: ' . $this->barang_id);
            $newStock = new Stock();
            $newStock->barang_id = $this->barang_id;
            $newStock->tambah_stock = $tanggal;
            $newStock->quantity_awal = $stock->quantity_akhir;

            if ($this->langsung_pakai == 1) {
                $newStock->quantity_masuk = 0;
                $newStock->quantity_keluar = $this->quantity_barang;
            } else {
                $newStock->quantity_masuk = $this->quantity_barang;
                $newStock->quantity_keluar = 0;
            }
            if ($newStock->quantity_keluar == 0) {
                $newStock->quantity_akhir = $newStock->quantity_awal + $newStock->quantity_masuk;
            } else {
                $newStock->quantity_akhir = $newStock->quantity_awal;
            }
            $newStock->is_ready = ($newStock->quantity_akhir >= 0 && $this->langsung_pakai == 1) ? 1 : 0;
            $newStock->is_new = ($this->langsung_pakai == 0) ? 1 : 0;
            $newStock->user_id = $userId;

            // Simpan data stock
            if (!$newStock->save()) {
                Yii::error('Failed to save stock: ' . print_r($newStock->errors, true));
            }
        }
    }
}
