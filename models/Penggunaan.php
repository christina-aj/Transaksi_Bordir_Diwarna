<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penggunaan".
 *
 * @property int $penggunaan_id
 * @property int $barang_id
 * @property int $user_id
 * @property int $jumlah_digunakan
 * @property string $tanggal_digunakan
 *
 * @property Barang $barang
 */
class Penggunaan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penggunaan';
    }

    /**
     * {@inheritdoc}
     */

    public $stock;
    public function rules()
    {
        return [
            [['barang_id', 'jumlah_digunakan', 'tanggal_digunakan', "user_id"], 'required'],
            [['barang_id', 'jumlah_digunakan', 'user_id'], 'integer'],
            [['tanggal_digunakan', 'stock'], 'safe'],
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
            'penggunaan_id' => 'Penggunaan ID',
            'barang_id' => 'Barang ID',
            'user_id' => 'User ID',
            'catatan' => 'Catatan',
            'jumlah_digunakan' => 'Jumlah Digunakan',
            'tanggal_digunakan' => 'Tanggal Digunakan',
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
            $this->tambah_stock = Yii::$app->formatter->asDate($this->tanggal_digunakan, 'php:Y-m-d');
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->updateStock();
    }

    protected function updateStock()
    {
        Yii::info('Updating stock for barang_id: ' . $this->barang_id);

        // Cek apakah stock untuk barang_id ini sudah ada
        $stock = Stock::find()->where(['barang_id' => $this->barang_id])
            ->orderBy(['stock_id' => SORT_DESC]) // Mengurutkan berdasarkan stock_id untuk mendapatkan yang terbaru
            ->one();

        if ($stock) {
            // Jika stock ada, buat entri stock baru
            Yii::info('Creating new stock entry for barang_id: ' . $this->barang_id);
            $newstock = new Stock();
            $newstock->barang_id = $this->barang_id;
            $newstock->tambah_stock = $this->tanggal_digunakan; // Pastikan tanggal_digunakan diambil dengan benar
            $newstock->quantity_awal = $stock->quantity_akhir; // Mengambil quantity akhir dari stock sebelumnya
            $newstock->quantity_masuk = 0; // Tidak ada barang yang masuk
            $newstock->quantity_keluar = $this->jumlah_digunakan; // Barang yang digunakan/keluar
            $newstock->quantity_akhir = $newstock->quantity_awal - $newstock->quantity_keluar; // Hitung quantity akhir
            $newstock->is_ready = 1; // Set is_ready
            $newstock->is_new = 0; // Set is_new karena sudah tidak baru
            $newstock->user_id = $this->user_id; // Set user_id dari pengguna yang terkait

            // Simpan data stock baru
            if (!$newstock->save()) {
                Yii::error('Failed to save new stock: ' . print_r($newstock->errors, true));
            }
        } else {
            Yii::error('No previous stock found for barang_id: ' . $this->barang_id);
        }
    }
}
