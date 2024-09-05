<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "laporanproduksi".
 *
 * @property int $laporan_id
 * @property int $mesin_id
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

     public function behaviors()
     {
         return [
             [
                 'class' => TimestampBehavior::className(),
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['tanggal_kerja'],
                 ],
                 'value' => new Expression('NOW()'), 
             ],
         ];
     }

    public function rules()
    {
        return [
            [['mesin_id', 'shift_id', 'nama_kerjaan', 'vs', 'stitch', 'kuantitas', 'bs'], 'required'],
            [['mesin_id', 'shift_id', 'vs', 'stitch', 'kuantitas', 'bs'], 'integer'],
            [['tanggal_kerja'], 'safe'],
            [['nama_kerjaan'], 'string', 'max' => 200],
            [['mesin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mesin::class, 'targetAttribute' => ['mesin_id' => 'mesin_id']],
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
            'mesin_id' => 'Mesin ID',
            'shift_id' => 'Shift ID',
            'tanggal_kerja' => 'Tanggal Kerja',
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
    public function getMesin()
    {
        return $this->hasOne(Mesin::class, ['mesin_id' => 'mesin_id']);
    }

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

}
