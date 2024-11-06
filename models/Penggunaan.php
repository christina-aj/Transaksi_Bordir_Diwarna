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
    public $nama_barang;
    public $kode_barang;
    public function rules()
    {
        return [
            [['barang_id', 'jumlah_digunakan', 'user_id'], 'required'],
            [['barang_id', 'jumlah_digunakan', 'user_id'], 'integer'],
            [['tanggal_digunakan', 'stock', 'nama_barang', 'kode_barang'], 'safe'],
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
    public function getGudang()
    {
        return $this->hasOne(Gudang::class, ['barang_id' => 'barang_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Mengubah format tanggal dari dd-mm-yyyy ke yyyy-mm-dd sebelum disimpan
            $this->tanggal_digunakan = Yii::$app->formatter->asDate($this->tanggal_digunakan, 'php:Y-m-d');
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

    protected function updateStock() {}
}
