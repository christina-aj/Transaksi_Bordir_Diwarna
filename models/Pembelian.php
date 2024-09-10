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
            [['total_biaya'], 'number', 'min' => 0],  // Validasi untuk angka minimal 0
            [['total_biaya'], 'default', 'value' => 0],
            [['kode_struk'], 'string', 'max' => 255],
            [['kode_struk'], 'unique'],
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
    public function getPembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class, ['pembelian_id' => 'pembelian_id']);
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

    public function updateTotalBiaya($pembelianId)
    {
        // Menghitung total biaya dari semua detail pembelian menggunakan relasi
        $totalBiaya = $this->getPembelianDetails()
            ->where(['pembelian_id' => $pembelianId])
            ->sum('total_biaya');

        // Update total biaya pada tabel pembelian
        $this->total_biaya = $totalBiaya;
        return $this->save(false); // Save tanpa validasi ulang
    }


    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Mengubah format tanggal dari dd-mm-yyyy ke yyyy-mm-dd sebelum disimpan
            $this->tanggal = Yii::$app->formatter->asDate($this->tanggal, 'php:Y-m-d');
            return true;
        } else {
            return false;
        }
    }
}
