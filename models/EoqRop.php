<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eoq_rop".
 *
 * @property int $EOQ_ROP_id
 * @property int $barang_id
 * @property float $biaya_pesan_snapshot
 * @property float $biaya_simpan_snapshot
 * @property float $safety_stock_snapshot
 * @property int $lead_time_snapshot
 * @property float $demand_snapshot
 * @property float $total_biaya_persediaan
 * @property float|null $hasil_eoq
 * @property float|null $hasil_rop
 * @property string|null $periode
 * @property string $created_at
 *
 * @property Barang $barang
 */
class EoqRop extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eoq_rop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hasil_eoq', 'hasil_rop', 'periode'], 'default', 'value' => null],
            [['total_biaya_persediaan'], 'default', 'value' => 0],
            [['barang_id', 'lead_time_snapshot'], 'required'],
            [['barang_id', 'lead_time_snapshot'], 'integer'],
            [['biaya_pesan_snapshot', 'biaya_simpan_snapshot', 'safety_stock_snapshot', 'demand_snapshot', 'total_biaya_persediaan', 'hasil_eoq', 'hasil_rop'], 'number'],
            [['periode', 'created_at'], 'safe'],
            [['barang_id'], 'exist', 'skipOnError' => true, 'targetClass' => Barang::class, 'targetAttribute' => ['barang_id' => 'barang_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'EOQ_ROP_id' => 'Eoq Rop ID',
            'barang_id' => 'Barang ID',
            'biaya_pesan_snapshot' => 'Biaya Pesan Snapshot',
            'biaya_simpan_snapshot' => 'Biaya Simpan Snapshot',
            'safety_stock_snapshot' => 'Safety Stock Snapshot',
            'lead_time_snapshot' => 'Lead Time Snapshot',
            'demand_snapshot' => 'Demand Snapshot',
            'total_biaya_persediaan' => 'Total Biaya Persediaan',
            'hasil_eoq' => 'Hasil Eoq',
            'hasil_rop' => 'Hasil Rop',
            'periode' => 'Periode',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Barang]].
     *
     * @return \yii\db\ActiveQuery
    //  */
    // public function getBarang()
    // {
    //     return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
    // }

    public function getBarang()
    {
        return $this->hasOne(\app\models\Barang::class, ['barang_id' => 'barang_id']);
    }

    /**
     * Konversi periode forecast ke format yang mudah dibaca
     * Contoh: 202508 menjadi "Agustus 2025"
     * 
     * @return string
     */
    // public function getPeriodeFormatted()
    // {
    //     if (empty($this->periode)) {
    //         return '-';
    //     }

    //     $bulan = $this->periode % 100;
    //     $tahun = floor($this->periode / 100);

    //     $namaBulan = [
    //         1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    //         5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    //         9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    //     ];

    //     return ($namaBulan[$bulan] ?? '') . ' ' . $tahun;
    // }

    public function getPeriodeFormatted()
    {
        if (empty($this->periode)) {
            return '-';
        }

        $bulanMap = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
            '05' => 'Mei', '06' => 'Jun', '07' => 'Jul', '08' => 'Agu',
            '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        // Cek format range
        if (strpos($this->periode, '-') !== false) {
            list($awal, $akhir) = explode('-', $this->periode);
            
            $tahunAwal = substr($awal, 0, 4);
            $bulanAwal = substr($awal, 4, 2);
            $tahunAkhir = substr($akhir, 0, 4);
            $bulanAkhir = substr($akhir, 4, 2);

            return $bulanMap[$bulanAwal] . ' ' . $tahunAwal . ' - ' . 
                $bulanMap[$bulanAkhir] . ' ' . $tahunAkhir;
        }

        // Format lama single period
        $tahun = substr($this->periode, 0, 4);
        $bulan = substr($this->periode, 4, 2);
        
        $bulanFull = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        return $bulanFull[$bulan] . ' ' . $tahun;
    }

}
