<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "stock_rop".
 *
 * @property int $stock_rop_id
 * @property int $barang_id
 * @property string|null $periode
 * @property int|null $stock_barang
 * @property int|null $safety_stock
 * @property int|null $jumlah_eoq
 * @property int|null $jumlah_rop
 * @property int|null $pesan_barang 0=tidak, 1=ya
 */
class StockRop extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock_rop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periode', 'stock_barang', 'safety_stock', 'jumlah_eoq', 'jumlah_rop'], 'default', 'value' => null],
            [['pesan_barang'], 'default', 'value' => 0],
            [['barang_id'], 'required'],
            [['barang_id', 'stock_barang', 'safety_stock', 'jumlah_eoq', 'jumlah_rop', 'pesan_barang'], 'integer'],
            [['periode'], 'string', 'max' => 7],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'stock_rop_id' => 'Stock Rop ID',
            'barang_id' => 'Barang ID',
            'periode' => 'Periode',
            'stock_barang' => 'Stock Barang',
            'safety_stock' => 'Safety Stock',
            'jumlah_eoq' => 'Jumlah Eoq',
            'jumlah_rop' => 'Jumlah Rop',
            'pesan_barang' => 'Pesan Barang',
        ];
    }

    public function getBarang()
    {
        return $this->hasOne(Barang::class, ['barang_id' => 'barang_id']);
    }

    // Method untuk status real-time
    public function getStatusPesan()
    {
        $stockBarang = $this->stock_barang ?? 0;
        $rop = $this->jumlah_rop ?? 0;
        $safetyStock = $this->safety_stock ?? 0;
        
        if ($stockBarang <= $rop) {
            return 'Pesan Sekarang';
        } elseif ($safetyStock > 0 && $stockBarang <= $safetyStock) {
            return 'Perlu Diperhatikan';
        }
        
        return 'Aman';
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
