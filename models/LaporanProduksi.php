<?php

namespace app\models;


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
            [['nama_mesin', 'shift_id', 'nama_kerjaan', 'kuantitas', 'bs','nama_barang'], 'required'],
            [['shift_id', 'vs', 'stitch', 'kuantitas', 'bs'], 'integer'],
            [['tanggal_kerja','nama_mesin', 'vs', 'stitch','berat'], 'safe'],
            [['nama_kerjaan','nama_mesin','berat'], 'string', 'max' => 200],
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
            'berat' => 'Berat',
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

    public function afterFind()
    {
        parent::afterFind();
        if ($this->tanggal_kerja) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $this->tanggal_kerja);
            if ($dateTime) {
                $this->tanggal_kerja = $dateTime->format('d-m-Y');
            }
        }
    }

    /**
     * Mengkonversi format tanggal dari d-m-Y ke Y-m-d sebelum disimpan ke database
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->tanggal_kerja) {
                $dateTime = \DateTime::createFromFormat('d-m-Y', $this->tanggal_kerja);
                if ($dateTime) {
                    $this->tanggal_kerja = $dateTime->format('Y-m-d');
                } else {
                    // Coba parse format Y-m-d jika format d-m-Y gagal
                    $dateTime = \DateTime::createFromFormat('Y-m-d', $this->tanggal_kerja);
                    if (!$dateTime) {
                        $this->addError('tanggal_kerja', 'Format tanggal tidak valid.');
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }


}
