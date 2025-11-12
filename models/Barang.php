<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "barang".
 *
 * @property int $barang_id
 * @property string $kode_barang
 * @property string $nama_barang
 * @property int $unit_id
 * @property float $angka
 * @property string $tipe
 * @property string|null $warna
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property PembelianDetail[] $pembelianDetails
 * @property Stock[] $stocks
 * @property Unit $unit
 */
class Barang extends \yii\db\ActiveRecord
{
    const KODE_BARANG_MENTAH = 1;
    const KODE_BARANG_NON_CONSUM = 2;

    const KATEGORI_FAST_MOVING = 1;
    const KATEGORI_SLOW_MOVING = 2;
    const KATEGORI_NON_MOVING = 3;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'barang';
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
            [['kode_barang', 'nama_barang', 'unit_id', 'tipe', 'kategori_barang'], 'required'],
            [['unit_id'], 'integer'],
            [['angka'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['kode_barang', 'nama_barang', 'tipe','warna'], 'string', 'max' => 255],
            [['kode_barang'], 'unique'],
            [['biaya_simpan_bulan', 'safety_stock'], 'integer'],
            [['unit_id'], 'exist', 'skipOnError' => true, 'targetClass' => Unit::class, 'targetAttribute' => ['unit_id' => 'unit_id']],
            [['jenis_barang'], 'in', 'range' => [self::KODE_BARANG_MENTAH,  self::KODE_BARANG_NON_CONSUM]],
            [['kategori_barang'], 'in', 'range' => [self::KATEGORI_FAST_MOVING,  self::KATEGORI_SLOW_MOVING, self::KATEGORI_NON_MOVING]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang ID',
            'kode_barang' => 'Kode Barang',
            'nama_barang' => 'Nama Barang',
            'angka' => 'Angka',
            'unit_id' => 'Satuan',
            'tipe' => 'Tipe',
            'kategori_barang' => 'Kategori Barang',
            'warna' => 'Warna',
            'biaya_simpan_bulan' => 'Biaya Simpan/Bulan',
            'safety_stock' => 'Safety Stock',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PembelianDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStocks()
    {
        return $this->hasMany(Stock::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Gets query for [[Unit]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::class, ['unit_id' => 'unit_id']);
    }

    /**
     * Scope untuk BARANG MENTAH / BAKU
     */
    public static function barangMentah()
    {
        return self::find()->where(['jenis_barang' => self::KODE_BARANG_MENTAH]);
    }

    // /**
    //  * Scope untuk BARANG SET JADI
    //  */
    // public static function barangSetJadi()
    // {
    //     return self::find()->where(['jenis_barang' => self::KODE_BARANG_SET_JADI]);
    // }

    /**
     * Scope untuk BARANG NON CONSUMABLE
     */
    public static function barangNonConsum()
    {
        return self::find()->where(['jenis_barang' => self::KODE_BARANG_NON_CONSUM]);
    }

    public function getJenisBarangLabel()
    {
        $labels = [
            self::KODE_BARANG_MENTAH => 'Barang Mentah',
            // self::KODE_BARANG_SET_JADI => 'Barang Set Jadi',
            self::KODE_BARANG_NON_CONSUM => 'Alat dan Mesin',
        ];
        return isset($labels[$this->jenis_barang]) ? $labels[$this->jenis_barang] : 'Unknown';
    }

    public function getKategoriBarangLabel()
    {
        $labels = [
            self::KATEGORI_FAST_MOVING => 'Fast Moving',
            self::KATEGORI_SLOW_MOVING => 'Slow Moving',
            self::KATEGORI_NON_MOVING => 'Non Moving',
        ];
        return isset($labels[$this->kategori_barang]) ? $labels[$this->kategori_barang] : 'Unknown';
    }
}
