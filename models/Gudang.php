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
 * @property int $kode (1 = barang gudang, 2 = penggunaan)
 * @property int $area_gudang (1, 2, 3, 4)
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
    const KODE_BARANG_GUDANG = 1;
    const KODE_PENGGUNAAN = 2;

    /**
     * {@inheritdoc}
     */
    public $nama_barang;
    public $kode_barang;
    
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
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['tanggal', 'barang_id', 'user_id', 'kode', 'quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir', 'area_gudang'], 'required'],
            [['tanggal', 'created_at', 'update_at'], 'safe'],
            [['barang_id', 'user_id', 'kode', 'area_gudang'], 'integer'],
            [['kode'], 'in', 'range' => [self::KODE_BARANG_GUDANG, self::KODE_PENGGUNAAN]],
            [['area_gudang'], 'in', 'range' => [1, 2, 3, 4]],
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
            'kode' => 'Kode',
            'area_gudang' => 'Area Gudang',
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

    /**
     * Scope untuk barang gudang
     */
    public static function barangGudang()
    {
        return self::find()->where(['kode' => self::KODE_BARANG_GUDANG]);
    }

    /**
     * Scope untuk penggunaan
     */
    public static function penggunaan()
    {
        return self::find()->where(['kode' => self::KODE_PENGGUNAAN]);
    }

    /**
     * Get stock terbaru untuk barang tertentu berdasarkan kode dan area
     */
    public static function getStockTerbaru($barang_id, $kode = self::KODE_BARANG_GUDANG, $area_gudang = null)
    {
        $query = self::find()
            ->where(['barang_id' => $barang_id, 'kode' => $kode])
            ->orderBy(['id_gudang' => SORT_DESC]);
            
        if ($area_gudang !== null) {
            $query->andWhere(['area_gudang' => $area_gudang]);
        }
        
        return $query->one();
    }

    /**
     * Get quantity akhir terbaru untuk barang tertentu
     */
    public static function getCurrentStock($barang_id, $kode = self::KODE_BARANG_GUDANG, $area_gudang = null)
    {
        $stock = self::getStockTerbaru($barang_id, $kode, $area_gudang);
        return $stock ? $stock->quantity_akhir : 0;
    }

    /**
     * Get stock berdasarkan area tertentu
     */
    public static function getCurrentStockByArea($barang_id, $area_gudang, $kode = self::KODE_BARANG_GUDANG)
    {
        return self::getCurrentStock($barang_id, $kode, $area_gudang);
    }

    /**
     * Get total stock semua area untuk barang tertentu
     */
    public static function getTotalStock($barang_id, $kode = self::KODE_BARANG_GUDANG)
    {
        $totalStock = 0;
        
        // Loop untuk setiap area (1-4)
        for ($area = 1; $area <= 4; $area++) {
            $stock = self::getCurrentStockByArea($barang_id, $area, $kode);
            $totalStock += $stock;
        }
        
        return $totalStock;
    }

    /**
     * Get stock breakdown per area untuk barang tertentu
     */
    public static function getStockByAreas($barang_id, $kode = self::KODE_BARANG_GUDANG)
    {
        $stockByArea = [];
        
        for ($area = 1; $area <= 4; $area++) {
            $stock = self::getCurrentStockByArea($barang_id, $area, $kode);
            $stockByArea[$area] = $stock;
        }
        
        return $stockByArea;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Mengubah format tanggal dari dd-mm-yyyy ke yyyy-mm-dd sebelum disimpan
            $this->tanggal = Yii::$app->formatter->asDate($this->tanggal, 'php:Y-m-d');
            
            // Set default area_gudang jika belum di-set
            if (empty($this->area_gudang)) {
                $this->area_gudang = 1;
            }
            
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get label untuk kode
     */
    public function getKodeLabel()
    {
        $labels = [
            self::KODE_BARANG_GUDANG => 'Barang Gudang',
            self::KODE_PENGGUNAAN => 'Penggunaan',
        ];
        return isset($labels[$this->kode]) ? $labels[$this->kode] : 'Unknown';
    }

    /**
     * Get label untuk area gudang
     */
    public function getAreaLabel()
    {
        $labels = [
            1 => 'Depan',
            2 => 'Bawah Tangga',
            3 => 'Lantai Dua',
            4 => 'Area Produksi',
            5 => 'Garasi (Barang Jadi)',
        ];
        return isset($labels[$this->area_gudang]) ? $labels[$this->area_gudang] : 'Unknown';
    }

    /**
     * Get array options untuk dropdown area
     */
    public static function getAreaOptions()
    {
        return [
            1 => 'Depan',
            2 => 'Bawah Tangga',
            3 => 'Lantai Dua',
            4 => 'Area Produksi',
            5 => 'Garasi (Barang Jadi)',
        ];
    }

    /**
     * Auto-update Stock ROP setiap kali ada perubahan stock gudang
     * Hanya trigger untuk kode=1 (Barang Gudang) dan jika quantity berubah
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Hanya update Stock ROP untuk barang gudang (bukan penggunaan)
        if ($this->kode == self::KODE_BARANG_GUDANG) {
            // Trigger jika insert baru atau quantity_akhir berubah
            $shouldUpdate = $insert || 
                        (isset($changedAttributes['quantity_akhir']) && 
                            $changedAttributes['quantity_akhir'] != $this->quantity_akhir);
            
            if ($shouldUpdate) {
                try {
                    // Update Stock ROP untuk barang ini
                    \app\controllers\StockRopController::updateStockForBarang($this->barang_id);
                    
                    Yii::info("Stock ROP updated for barang_id: {$this->barang_id}", __METHOD__);
                } catch (\Exception $e) {
                    // Log error tapi jangan gagalkan transaksi utama
                    Yii::error("Failed to update Stock ROP for barang_id {$this->barang_id}: " . $e->getMessage(), __METHOD__);
                }
            }
        }
    }
}