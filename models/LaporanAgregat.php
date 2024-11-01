<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "laporanproduksi".
 *
 * @property int $laporan_id
 * @property int $shift_id
 * @property string $tanggal_kerja
 * @property string $nama_kerjaan
 * @property int $vs
 * @property int $stitch
 * @property int $kuantitas
 * @property int $bs
 *
 * @property Shift $shift
 */
class LaporanAgregat extends \yii\db\ActiveRecord
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
            [['tanggal_kerja', 'nama_kerjaan','kuantitas'], 'required'],
            [[ 'kuantitas'], 'integer'],
            [['tanggal_kerja'], 'safe'],
            [['nama_kerjaan'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tanggal_kerja' => 'Tanggal Kerja',
            'nama_kerjaan' => 'Nama Kerjaan',
            'kuantitas' => 'Kuantitas',
        ];
    }

    public static function getMonthlyAggregatedData()
    {
        $subquery = (new \yii\db\Query())
        ->select([
            'nama',
            'SUM(qty) AS total_keluar_qty'
        ])
        ->from('laporan_keluar')
        ->groupBy(['nama']);

 
        return self::find()
            ->select([
                'MONTH(tanggal_kerja) AS month',
                'YEAR(tanggal_kerja) AS year',
                'nama_barang',
                'nama_kerjaan',
                'SUM(kuantitas) - COALESCE(lk.total_keluar_qty, 0) AS total_kuantitas'
            ])
            ->leftJoin(['lk' => $subquery], 'lk.nama = laporanproduksi.nama_kerjaan')
            ->groupBy(['MONTH(tanggal_kerja)', 'YEAR(tanggal_kerja)', 'nama_kerjaan'])
            ->asArray()
            ->all();
        
    }

    public static function getFilterAggregatedData($year = null, $month = null, $nama_kerjaan = null, $startDate = null, $endDate = null)
    {
        $query = self::find()
            ->select([
                'MONTH(tanggal_kerja) AS month',
                'YEAR(tanggal_kerja) AS year',
                'nama_kerjaan',
                'nama_barang',
                'SUM(kuantitas) AS total_kuantitas'
            ])
            ->groupBy(['MONTH(tanggal_kerja)', 'YEAR(tanggal_kerja)', 'nama_kerjaan']);

        // Apply filters
        if ($year) {
            $query->andWhere(['YEAR(tanggal_kerja)' => $year]);
        }
        if ($month) {
            $query->andWhere(['MONTH(tanggal_kerja)' => $month]);
        }
        if ($nama_kerjaan) {
            $query->andWhere(['like', 'nama_kerjaan', $nama_kerjaan]);
        }
        if ($startDate && $endDate) {
            $query->andWhere(['between', 'tanggal_kerja', $startDate, $endDate]);
        }

        return $query->asArray()->all();
    }

}