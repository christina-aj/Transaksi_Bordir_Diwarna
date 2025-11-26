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
                .table { width: 100%; border-collapse: collapse; font-size: 10px; }
                .table th, .table td { border: 1px solid #ddd; padding: 5px; }
                .table th { background-color: #4CAF50; color: white; text-align: center; font-weight: bold; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                h2 { color: #000000ff; margin-bottom: 5px; text-align: center; }
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
                h2 { color: #000000ff; margin-bottom: 5px; text-align: center; }
                .subtitle { text-align: center; font-size: 10px; margin-bottom: 15px; }
                .area-header { background-color: #f8f9fa; font-weight: bold; }
                .total-row { background-color: #fff3cd; font-weight: bold; }
            ',
            'options' => ['title' => 'Ringkasan Mutasi Gudang'],
            'methods' => [
                'SetHeader' => ['Ringkasan Mutasi Gudang - ' . date('d/m/Y H:i')],
                'SetFooter' => ['Halaman {PAGENO} dari {nbpg}'],
            ]
        ]);
        
        return 
        
        $pdf->render();
    }
    
    /**
     * Laporan Kartu Stok per Barang
     */
    public function actionKartuStok($barang_id = null, $area_gudang = null, $tanggal_dari = null, $tanggal_sampai = null)
    {
        // List barang
        $barangQuery = "SELECT barang_id, CONCAT(kode_barang, ' - ', nama_barang) as nama 
                        FROM inventaris_web.barang 
                        ORDER BY kode_barang";
        $barangData = Yii::$app->db->createCommand($barangQuery)->queryAll();
        $barangList = [];
        foreach ($barangData as $item) {
            $barangList[$item['barang_id']] = $item['nama'];
        }
        
        // List area gudang
        $areaGudangList = Gudang::find()
            ->select(['area_gudang'])
            ->distinct()
            ->orderBy('area_gudang')
            ->column();
        
        $kartuStokData = [];
        $barang = null;
        $stokAwal = 0;
        
        if ($barang_id && $area_gudang) {
            // Ambil data barang
            $barang = Barang::findOne($barang_id);
            
            // Hitung saldo awal (sebelum tanggal_dari)
            if ($tanggal_dari) {
                $stokAwalQuery = "
                    SELECT quantity_akhir 
                    FROM inventaris_web.gudang 
                    WHERE barang_id = :barang_id 
                    AND area_gudang = :area_gudang
                    AND tanggal < :tanggal_dari
                    ORDER BY tanggal DESC, id_gudang DESC
                    LIMIT 1
                ";
                $stokAwalResult = Yii::$app->db->createCommand($stokAwalQuery, [
                    ':barang_id' => $barang_id,
                    ':area_gudang' => $area_gudang,
                    ':tanggal_dari' => $tanggal_dari
                ])->queryScalar();
                
                $stokAwal = $stokAwalResult ? $stokAwalResult : 0;
            }
            
            // Query transaksi
            $query = "
                SELECT 
                    g.id_gudang,
                    g.tanggal,
                    g.area_gudang,
                    g.catatan,
                    g.quantity_awal,
                    g.quantity_masuk,
                    g.quantity_keluar,
                    g.quantity_akhir as stok,
                    b.kode_barang,
                    b.nama_barang,
                    b.warna,
                    b.unit_id
                FROM inventaris_web.gudang g
                LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
                WHERE g.barang_id = :barang_id
                    AND g.area_gudang = :area_gudang
            ";
            
            $params = [
                ':barang_id' => $barang_id,
                ':area_gudang' => $area_gudang
            ];
            
            if ($tanggal_dari) {
                $query .= " AND g.tanggal >= :tanggal_dari";
                $params[':tanggal_dari'] = $tanggal_dari;
            }
            
            if ($tanggal_sampai) {
                $query .= " AND g.tanggal <= :tanggal_sampai";
                $params[':tanggal_sampai'] = $tanggal_sampai;
            }
            
            $query .= " ORDER BY g.tanggal ASC, g.id_gudang ASC";
            
            $kartuStokData = Yii::$app->db->createCommand($query, $params)->queryAll();
        }
        
        return $this->render('kartu-stok', [
            'barangList' => $barangList,
            'areaGudangList' => $areaGudangList,
            'kartuStokData' => $kartuStokData,
            'barang' => $barang,
            'stokAwal' => $stokAwal,
            'barang_id' => $barang_id,
            'area_gudang' => $area_gudang,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ]);
    }

    /**
     * Download PDF Laporan Kartu Stok
     */
    public function actionKartuStokPdf($barang_id = null, $area_gudang = null, $tanggal_dari = null, $tanggal_sampai = null)
    {
        $barang = null;
        $stokAwal = 0;
        $kartuStokData = [];
        
        if ($barang_id && $area_gudang) {
            // Ambil data barang
            $barang = Barang::findOne($barang_id);
            
            // Hitung saldo awal (sebelum tanggal_dari)
            if ($tanggal_dari) {
                $stokAwalQuery = "
                    SELECT quantity_akhir 
                    FROM inventaris_web.gudang 
                    WHERE barang_id = :barang_id 
                    AND area_gudang = :area_gudang
                    AND tanggal < :tanggal_dari
                    ORDER BY tanggal DESC, id_gudang DESC
                    LIMIT 1
                ";
                $stokAwalResult = Yii::$app->db->createCommand($stokAwalQuery, [
                    ':barang_id' => $barang_id,
                    ':area_gudang' => $area_gudang,
                    ':tanggal_dari' => $tanggal_dari
                ])->queryScalar();
                
                $stokAwal = $stokAwalResult ? $stokAwalResult : 0;
            }
            
            // Query transaksi
            $query = "
                SELECT 
                    g.id_gudang,
                    g.tanggal,
                    g.area_gudang,
                    g.catatan as keterangan,
                    g.quantity_awal,
                    g.quantity_masuk,
                    g.quantity_keluar,
                    g.quantity_akhir as stok,
                    b.kode_barang,
                    b.nama_barang,
                    b.warna,
                    b.unit_id
                FROM inventaris_web.gudang g
                LEFT JOIN inventaris_web.barang b ON b.barang_id = g.barang_id
                WHERE g.barang_id = :barang_id
                    AND g.area_gudang = :area_gudang
            ";
            
            $params = [
                ':barang_id' => $barang_id,
                ':area_gudang' => $area_gudang
            ];
            
            if ($tanggal_dari) {
                $query .= " AND g.tanggal >= :tanggal_dari";
                $params[':tanggal_dari'] = $tanggal_dari;
            }
            
            if ($tanggal_sampai) {
                $query .= " AND g.tanggal <= :tanggal_sampai";
                $params[':tanggal_sampai'] = $tanggal_sampai;
            }
            
            $query .= " ORDER BY g.tanggal ASC, g.id_gudang ASC";
            
            $kartuStokData = Yii::$app->db->createCommand($query, $params)->queryAll();
        }
        
        // Render PDF
        $content = $this->renderPartial('_kartu-stok-pdf', [
            'barang' => $barang,
            'kartuStokData' => $kartuStokData,
            'stokAwal' => $stokAwal,
            'area_gudang' => $area_gudang,
            'tanggal_dari' => $tanggal_dari,
            'tanggal_sampai' => $tanggal_sampai,
        ]);
        
        // Setup PDF
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssInline' => '
                body { font-family: Arial, sans-serif; font-size: 11px; }
                .table { width: 100%; border-collapse: collapse; }
                .table th { background-color: #4CAF50; color: white; padding: 6px; text-align: center; border: 1px solid #000; font-weight: bold; }
                .table td { padding: 5px; border: 1px solid #333; font-size: 10px; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .stok-awal { background-color: #fff3cd; font-weight: bold; }
                .total-row { background-color: #e3f2fd; font-weight: bold; }
                .total-stok { background-color: #37903cff; color: #000000ff; }
                h2, h3 { color: #000000ff; margin: 5px; text-align: center; }
                .barang-info { margin: 15px 0; padding: 10px; border: 1px solid #333; background-color: #f9f9f9; }
                .barang-info table { width: 100%; }
                .barang-info td { padding: 3px 8px; font-size: 11px; }
                .barang-info td:nth-child(odd) { width: 120px; font-weight: bold; }
            ',
            'options' => [
                'title' => 'Kartu Stok',
                'subject' => 'Laporan Kartu Stok',
                'defaultheaderline' => 0,
                'defaultfooterline' => 0,
            ],
            'methods' => [
                'SetHeader' => [''],
                'SetFooter' => ['Halaman {PAGENO} dari {nbpg}'],
            ],
            'filename' => 'Kartu_Stok_' . ($barang ? $barang->kode_barang : 'all') . '_' . date('YmdHis') . '.pdf',
        ]);
        
        return $pdf->render();
    }
}