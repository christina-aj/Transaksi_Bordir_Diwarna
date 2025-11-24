<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Barang;
use app\models\Gudang;
use kartik\mpdf\Pdf;
use yii\db\Expression;

class LaporanBarangController extends Controller
{
    /**
     * Laporan Stok per Area Gudang
     */
    public function actionStokGudang($area_gudang = null, $tanggal = null)
    {
        // List area gudang yang tersedia
        $areaGudangList = Gudang::find()
            ->select(['area_gudang'])
            ->distinct()
            ->orderBy('area_gudang')
            ->column();
        
        // Base query untuk ambil data terakhir per area
        $query = "
            SELECT 
                g.barang_id,
                g.area_gudang,
                g.kode,
                b.kode_barang,
                b.nama_barang,
                b.warna,
                b.kategori_barang,
                b.unit_id,
                g.quantity_akhir as total_stok
            FROM inventaris_web.gudang g
            LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
            WHERE g.id_gudang IN (
                SELECT MAX(g2.id_gudang)
                FROM inventaris_web.gudang g2
                WHERE g2.barang_id = g.barang_id
                AND g2.kode = g.kode
                AND g2.area_gudang = g.area_gudang
                AND g2.created_at = (
                    SELECT MAX(g3.created_at)
                    FROM inventaris_web.gudang g3
                    WHERE g3.barang_id = g2.barang_id
                    AND g3.kode = g2.kode
                    AND g3.area_gudang = g2.area_gudang
                )
                GROUP BY g2.area_gudang
            )
        ";
        
        $params = [];
        
        // Tambahkan filter area_gudang
        if ($area_gudang) {
            $query .= " AND g.area_gudang = :area";
            $params[':area'] = $area_gudang;
        }
        
        // Tambahkan filter tanggal
        if ($tanggal) {
            $query .= " AND DATE(g.created_at) <= :tanggal";
            $params[':tanggal'] = $tanggal;
        }
        
        $query .= " ORDER BY g.area_gudang ASC, g.kode ASC";
        
        $stokData = Yii::$app->db->createCommand($query, $params)->queryAll();
        
        return $this->render('stok-gudang', [
            'areaGudangList' => $areaGudangList,
            'stokData' => $stokData,
            'area_gudang' => $area_gudang,
            'tanggal' => $tanggal,
        ]);
    }
    
    /**
     * Download PDF Laporan Stok per Gudang
     */
    public function actionStokGudangPdf($area_gudang = null, $tanggal = null)
    {
        // Query stok dengan data barang - ambil baris terakhir per area
        $query = "
            SELECT 
                g.barang_id,
                g.area_gudang,
                g.kode,
                b.kode_barang,
                b.nama_barang,
                b.warna,
                b.kategori_barang,
                b.unit_id,
                g.quantity_akhir as total_stok
            FROM inventaris_web.gudang g
            LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
            WHERE g.id_gudang IN (
                SELECT MAX(g2.id_gudang)
                FROM inventaris_web.gudang g2
                WHERE g2.barang_id = g.barang_id
                AND g2.kode = g.kode
                AND g2.area_gudang = g.area_gudang
                AND g2.created_at = (
                    SELECT MAX(g3.created_at)
                    FROM inventaris_web.gudang g3
                    WHERE g3.barang_id = g2.barang_id
                    AND g3.kode = g2.kode
                    AND g3.area_gudang = g2.area_gudang
                )
                GROUP BY g2.area_gudang
            )
        ";
        
        $params = [];
        
        // Tambahkan filter area_gudang
        if ($area_gudang) {
            $query .= " AND g.area_gudang = :area";
            $params[':area'] = $area_gudang;
        }
        
        // Tambahkan filter tanggal
        if ($tanggal) {
            $query .= " AND DATE(g.created_at) <= :tanggal";
            $params[':tanggal'] = $tanggal;
        }
        
        $query .= " ORDER BY g.area_gudang ASC, g.kode ASC";
        
        $stokData = Yii::$app->db->createCommand($query, $params)->queryAll();
        
        $areaGudangNama = $area_gudang ? 'Area Gudang ' . $area_gudang : 'Semua Area Gudang';
        
        $content = $this->renderPartial('_stok-gudang-pdf', [
            'stokData' => $stokData,
            'areaGudangNama' => $areaGudangNama,
            'tanggal' => $tanggal,
        ]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                .table { width: 100%; border-collapse: collapse; font-size: 11px; }
                .table th, .table td { border: 1px solid #ddd; padding: 6px; }
                .table th { background-color: #4CAF50; color: white; text-align: left; font-weight: bold; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                h2 { color: #333; margin-bottom: 5px; }
                .info { color: #666; font-size: 10px; margin-bottom: 15px; }
                .group-header { background-color: #e8f5e9; font-weight: bold; }
            ',
            'options' => ['title' => 'Laporan Stok per Gudang'],
            'methods' => [
                'SetHeader' => ['Laporan Stok per Gudang - ' . date('d/m/Y H:i')],
                'SetFooter' => ['Halaman {PAGENO} dari {nbpg}'],
            ]
        ]);
        
        return $pdf->render();
    }
    
    /**
     * Laporan Ringkasan Mutasi Gudang
     */
    public function actionRingkasanMutasi($tanggal_dari = null, $tanggal_sampai = null)
    {
        // List area gudang
        $areaGudangList = Gudang::find()
            ->select(['area_gudang'])
            ->distinct()
            ->orderBy('area_gudang')
            ->column();
        
        // Query untuk ringkasan mutasi per area dan barang
        $query = "
            SELECT 
                g.area_gudang,
                g.barang_id,
                b.kode_barang,
                b.nama_barang,
                b.unit_id,
                MIN(g.quantity_awal) as saldo_awal,
                SUM(g.quantity_masuk) as total_masuk,
                SUM(g.quantity_keluar) as total_keluar,
                MAX(g.quantity_akhir) as saldo_akhir
            FROM inventaris_web.gudang g
            LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($tanggal_dari) {
            $query .= " AND g.tanggal >= :dari";
            $params[':dari'] = $tanggal_dari;
        }
        
        if ($tanggal_sampai) {
            $query .= " AND g.tanggal <= :sampai";
            $params[':sampai'] = $tanggal_sampai;
        }
        
        $query .= " GROUP BY g.area_gudang, g.barang_id, b.kode_barang, b.nama_barang, b.unit_id
                    ORDER BY g.area_gudang ASC, b.nama_barang ASC";
        
        $mutasiData = Yii::$app->db->createCommand($query, $params)->queryAll();
        
        return $this->render('ringkasan-mutasi', [
            'areaGudangList' => $areaGudangList,
            'mutasiData' => $mutasiData,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ]);
    }

    /**
     * Download PDF Laporan Ringkasan Mutasi
     */
    public function actionRingkasanMutasiPdf($tanggal_dari = null, $tanggal_sampai = null)
    {
        // Query untuk ringkasan mutasi per area dan barang
        $query = "
            SELECT 
                g.area_gudang,
                g.barang_id,
                b.kode_barang,
                b.nama_barang,
                b.unit_id,
                MIN(g.quantity_awal) as saldo_awal,
                SUM(g.quantity_masuk) as total_masuk,
                SUM(g.quantity_keluar) as total_keluar,
                MAX(g.quantity_akhir) as saldo_akhir
            FROM inventaris_web.gudang g
            LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($tanggal_dari) {
            $query .= " AND g.tanggal >= :dari";
            $params[':dari'] = $tanggal_dari;
        }
        
        if ($tanggal_sampai) {
            $query .= " AND g.tanggal <= :sampai";
            $params[':sampai'] = $tanggal_sampai;
        }
        
        $query .= " GROUP BY g.area_gudang, g.barang_id, b.kode_barang, b.nama_barang, b.unit_id
                    ORDER BY g.area_gudang ASC, b.nama_barang ASC";
        
        $mutasiData = Yii::$app->db->createCommand($query, $params)->queryAll();
        
        $content = $this->renderPartial('_ringkasan-mutasi-pdf', [
            'mutasiData' => $mutasiData,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                .table { width: 100%; border-collapse: collapse; font-size: 10px; }
                .table th, .table td { border: 1px solid #ddd; padding: 5px; }
                .table th { background-color: #e91e63; color: white; text-align: center; font-weight: bold; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                h2 { color: #e91e63; margin-bottom: 5px; text-align: center; }
                .subtitle { text-align: center; font-size: 11px; margin-bottom: 15px; }
                .area-header { background-color: #f8f9fa; font-weight: bold; }
                .total-row { background-color: #fff3cd; font-weight: bold; }
            ',
            'options' => ['title' => 'Ringkasan Mutasi Gudang'],
            'methods' => [
                'SetHeader' => ['Ringkasan Mutasi Gudang - ' . date('d/m/Y H:i')],
                'SetFooter' => ['Halaman {PAGENO} dari {nbpg}'],
            ]
        ]);
        
        return $pdf->render();
    }
}