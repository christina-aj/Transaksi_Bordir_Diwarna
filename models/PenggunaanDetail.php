<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "penggunaan_detail".
 *
 * @property int $gunadetail_id
 * @property int $penggunaan_id
 * @property int $barang_id
 * @property int $jumlah_digunakan
 * @property string|null $catatan
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Barang $barang
 * @property Penggunaan $penggunaan
 */
class PenggunaanDetail extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */

    public $nama_barang;
    public $kode_barang;
    public $kode_penggunaan;
    public $area_gudang;

    public static function tableName()
    {
        return 'penggunaan_detail';
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
            [['catatan', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['penggunaan_id', 'barang_id'], 'required'],
            [['penggunaan_id', 'barang_id'], 'integer'],
            [['jumlah_digunakan'], 'number', 'min' => 0.001, 'max' => 1000], // ← Float/number
            [['created_at', 'updated_at'], 'safe'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
            [['penggunaan_id'], 'exist', 'skipOnError' => true, 'targetClass' => Penggunaan::class, 'targetAttribute' => ['penggunaan_id' => 'penggunaan_id']],
            [['jumlah_digunakan'], 'required', 'message' => 'Jumlah harus diisi'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'gunadetail_id' => 'Gunadetail ID',
            'penggunaan_id' => 'penggunaan ID',
            'barang_id' => 'Barang ID',
            'jumlah_digunakan' => 'Jumlah Digunakan',
            'catatan' => 'Catatan',
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
    public function getNamaBarang()
    {
        if ($this->barang) {
            return $this->barang->kode_barang . ' - ' . $this->barang->nama_barang;
        }

        return null;
    }
    public function getKodeBarang()
    {
        if ($this->barang) {
            return $this->barang->kode_barang;
        }

        return null;
    }
    public function getGudang()
    {
        return $this->hasOne(Gudang::class, ['id_gudang' => 'id_gudang']);
    }

public function getAreaGudang()
    {
        if ($this->barang && $this->barang->gudang) {
            return $this->barang->gudang->area_gudang;
        }

        return null;
    }
    /**
     * Gets query for [[penggunaan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getpenggunaan()
    {
        return $this->hasOne(Penggunaan::class, ['penggunaan_id' => 'penggunaan_id']);
    }
    public function getFormattedGunaId()
    {
        return 'PG-' . str_pad((string) $this->penggunaan_id, 3, '0', STR_PAD_LEFT);
    }

    public function getFormattedGunaIdProperty($penggunaan_id)
    {
        return 'PG-' . str_pad((string) $penggunaan_id, 3, '0', STR_PAD_LEFT);
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Panggil fungsi updateTotalItem dari model Penggunaan
        $penggunaan = Penggunaan::findOne($this->penggunaan_id);
        if ($penggunaan) {
            $penggunaan->updateTotalItem($this->penggunaan_id);
        }
    }



}
