<?php

namespace app\models;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

use Yii;

/**
 * This is the model class for table "laporan_keluar".
 *
 * @property int $id
 * @property string $nama
 * @property int $qty
 * @property string $tanggal
 * @property string $catatan
 */
class LaporanKeluar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
     {
         return [
             [
                 'class' => TimestampBehavior::className(),
                 'attributes' => [
                     \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['tanggal'],
                 ],
                 'value' => new Expression('NOW()'), 
             ],
         ];
     }

    public static function tableName()
    {
        return 'laporan_keluar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama', 'qty', 'catatan','barang'], 'required'],
            [['qty'], 'integer'],
            [['tanggal'], 'safe'],
            [['catatan'], 'string'],
            [['nama'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barang' => 'Nama Barang',
            'nama' => 'Nama Kerjaan',
            'qty' => 'Qty',
            'tanggal' => 'Tanggal',
            'catatan' => 'Catatan',
        ];
    }
}
