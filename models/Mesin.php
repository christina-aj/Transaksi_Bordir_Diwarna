<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mesin".
 *
 * @property int $mesin_id
 * @property string $nama
 * @property string $deskripsi
 *
 * @property LaporanProduksi[] $laporanProduksis
 * @property Shift[] $shifts
 */
class Mesin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mesin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'deskripsi'], 'required'],
            [['deskripsi','kategori'], 'string'],
            [['nama'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mesin_id' => 'Mesin ID',
            'nama' => 'Nama',
            'kategori' => 'Kategori',
            'deskripsi' => 'Deskripsi',
        ];
    }

    /**
     * Gets query for [[LaporanProduksis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLaporanProduksis()
    {
        return $this->hasMany(LaporanProduksi::class, ['mesin_id' => 'mesin_id']);
    }

    /**
     * Gets query for [[Shifts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShifts()
    {
        return $this->hasMany(Shift::class, ['shift_id' => 'shift_id'])->viaTable('laporanproduksi', ['mesin_id' => 'mesin_id']);
    }

    public function getLaporan()
    {
        return $this->hasMany(LaporanProduksi::class, ['nama_mesin' => 'nama_mesin']);
    }
}
