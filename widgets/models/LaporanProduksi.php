<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "laporanproduksi".
 *
 * @property int $laporan_id
 * @property int $nama_mesin
 * @property int $shift_id
 * @property string $tanggal_kerja
 * @property string $nama_kerjaan
 * @property int $vs
 * @property int $stitch
 * @property int $kuantitas
 * @property int $bs
 *
 * @property Mesin $mesin
 * @property Shift $shift
 */
class laporanproduksi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'laporanproduksi';
    }

    /**
     * {@inheritdoc}
     */


    public function rules()
    {
        return [
            [['nama_mesin', 'shift_id', 'nama_kerjaan', 'vs', 'stitch', 'kuantitas', 'bs','nama_barang'], 'required'],
            [['shift_id', 'vs', 'stitch', 'kuantitas', 'bs'], 'integer'],
            [['tanggal_kerja','nama_mesin'], 'safe'],
            [['nama_kerjaan','nama_mesin'], 'string', 'max' => 200],
            [['shift_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shift::class, 'targetAttribute' => ['shift_id' => 'shift_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'laporan_id' => 'Laporan ID',
            'nama_mesin' => 'Nama Mesin',
            'shift_id' => 'Shift ID',
            'tanggal_kerja' => 'Tanggal Kerja',
            'nama_barang' => 'Nama Barang',
            'nama_kerjaan' => 'Nama Kerjaan',
            'vs' => 'Vs',
            'stitch' => 'Stitch',
            'kuantitas' => 'Kuantitas',
            'bs' => 'Bs',
        ];
    }

    /**
     * Gets query for [[Mesin]].
     *
     * @return \yii\db\ActiveQuery
     */

    /**
     * Gets query for [[Shift]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(Shift::class, ['shift_id' => 'shift_id']);
    }

    public function getNamaOperator()
    {
        return $this->shift ? $this->shift->nama_operator : null;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->tanggal_kerja) {
                $dateTime = \DateTime::createFromFormat('d-m-Y', $this->tanggal_kerja);
                if ($dateTime) {
                    $this->tanggal_kerja = $dateTime->format('Y-m-d');
                } else {
                    $this->addError('tanggal_kerja', 'Format tanggal tidak valid.');
                    return false; 
                }
            }
            return true;
        }
        return false;
    }


}
