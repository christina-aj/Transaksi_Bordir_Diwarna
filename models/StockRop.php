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
    public function getPeriodeFormatted()
    {
        if (empty($this->periode)) {
            return '-';
        }

        $bulan = $this->periode % 100;
        $tahun = floor($this->periode / 100);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return ($namaBulan[$bulan] ?? '') . ' ' . $tahun;
    }

}
