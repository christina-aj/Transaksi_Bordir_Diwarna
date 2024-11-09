<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "shift".
 *
 * @property int $shift_id
 * @property int $user_id
 * @property string $tanggal
 * @property string $shift
 * @property float $waktu_kerja
 * @property string $nama_operator
 * @property string $mulai_istirahat
 * @property string $selesai_istirahat
 * @property string $kendala
 * @property int $ganti_benang
 * @property int $ganti_kain
 *
 * @property LaporanProduksi[] $laporanProduksis
 * @property Mesin[] $mesins
 * @property User $user
 */
class Shift extends \yii\db\ActiveRecord
{   
    /**
     * {@inheritdoc}
     */
    public $start_time;
    public $end_time;

    public $waktu_kerja_hidden;

    public static function tableName()
    {
        return 'shift';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shift', 'waktu_kerja', 'nama_operator', 'mulai_istirahat', 'selesai_istirahat','ganti_benang', 'ganti_kain'], 'required'],
            [['user_id', 'ganti_benang', 'ganti_kain'], 'integer'],
            [['tanggal', 'mulai_istirahat', 'selesai_istirahat','start_time', 'end_time','kendala'], 'safe'],
            [['shift'], 'integer'],
            [['waktu_kerja'], 'number', 'min' => 0, 'max' => 1], 
            [['waktu_kerja_hidden'], 'string'],
            [['nama_operator'], 'string', 'max' => 200],
            [['user_id'], 'default', 'value' => Yii::$app->user->id], 
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
            [['start_time', 'end_time'], 'match', 'pattern' => '/^([01]\d|2[0-3]):([0-5]\d)$/'],
        ];
    }

    public function validateWaktuKerja($attribute, $params)
    {
        if (!is_numeric($this->$attribute) && !is_string($this->$attribute)) {
            $this->addError($attribute, 'Waktu Kerja must be either a string or a numeric value.');
        }
    }

    /**s
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shift_id' => 'Shift ID',
            'user_id' => 'User ID',
            'tanggal' => 'Tanggal',
            'shift' => 'Shift',
            'waktu_kerja' => 'Waktu Kerja',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'waktu_kerja_hidden' => 'waktu_kerja_hidden',
            'nama_operator' => 'Nama Operator',
            'mulai_istirahat' => 'Mulai Istirahat',
            'selesai_istirahat' => 'Selesai Istirahat',
            'kendala' => 'Kendala',
            'ganti_benang' => 'Ganti Benang',
            'ganti_kain' => 'Ganti Kain',
            
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->tanggal) {
                $dateTime = \DateTime::createFromFormat('d-m-Y', $this->tanggal);
                if ($dateTime) {
                    $this->tanggal = $dateTime->format('Y-m-d');
                } else {
                    $this->addError('tanggal', 'Format tanggal tidak valid.');
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    
    public function afterFind()
    {
        parent::afterFind();
        if ($this->tanggal) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $this->tanggal);
            if ($dateTime) {
                $this->tanggal = $dateTime->format('d-m-Y');
            }
        }
    }
    

    /**
     * Gets query for [[LaporanProduksis]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getLaporanProduksis()
    {
        return $this->hasMany(LaporanProduksi::class, ['shift_id' => 'shift_id']);
    }

    /**
     * Gets query for [[Mesins]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMesins()
    {
        return $this->hasMany(Mesin::class, ['mesin_id' => 'mesin_id'])->viaTable('laporan_produksi', ['shift_id' => 'shift_id']);
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
}
