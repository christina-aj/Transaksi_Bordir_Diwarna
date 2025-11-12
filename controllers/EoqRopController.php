<?php

namespace app\controllers;

use app\models\EoqRop;
use app\models\EoqRopSearch;
use app\models\Barang;
use app\models\Forecast;
use app\models\BomBarang;
use app\models\BomDetail;
use app\models\SupplierBarang;
use app\models\SupplierBarangDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * EoqRopController implements the CRUD actions for EoqRop model.
 */
class EoqRopController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all EoqRop models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EoqRopSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EoqRop model.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($EOQ_ROP_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($EOQ_ROP_id),
        ]);
    }

    /**
     * Creates a new EoqRop model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        //ambil semua periode forecasting
        $periodes = Forecast::find()
            ->select('periode_forecast')
            ->distinct()
            ->orderBy(['periode_forecast' => SORT_DESC])
            ->column();
        if (empty($periodes)) {
            Yii::$app->session->setFlash('error', 'Tidak ada data forecasting. Silakan lakukan forecasting terlebih dahulu.');
            return $this->redirect(['index']);
        }

        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        $warningMessages = [];

        //loop per periodenya
        foreach ($periodes as $periode) {
            // query buat total demand yg diperlukan (total bom)
            // join forecast, bom barang,bom detail
            $sql = "
                SELECT
                    bd.barang_id,
                    f.periode_forecast,
                    SUM(f.hasil_forecast * bd.qty_BOM) AS demand_snapshot
                FROM forecast f
                INNER JOIN BOM_barang bb on f.barang_produksi_id = bb.barang_produksi_id
                INNER JOIN BOM_detail bd on bb.BOM_barang_id = bd.BOM_barang_id
                WHERE f.periode_forecast = :periode
                GROUP BY bd.barang_id, f.periode_forecast
            ";

            $demandData = Yii::$app->db->createCommand($sql)
                ->bindValue(':periode', $periode)
                ->queryAll();

            //loop per bahan bakunya
            foreach ($demandData as $data) {
                try {
                    $barangId = $data['barang_id'];
                    $demandSnapshot = $data['demand_snapshot'];
                    // $QtyBom = $data['qty_BOM'];

                    //ambil data barang (bahan baku)
                    $barang = Barang::findOne($barangId);
                    if (!$barang) {
                        $errors[] = "Barang dengan ID $barangId tidak ditemukan.";
                        $errorCount++;
                        continue;
                    }

                    $namaBarang = $barang->nama_barang ?? "Barang ID {$barangId}";
                    $safetyStock = $barang->safety_stock ?? 0;
                    $biayaSimpan = $barang->biaya_simpan_bulan ?? 0;

                    // ambil data supp utama untuk barang tersebut
                    //alur - barang - supplier_barang - supplier_barang_detail (supp_utama = 1)
                    $supplierBarang = SupplierBarang::find()
                        ->where(['barang_id' => $barangId])
                        ->one();
                    if (!$supplierBarang) {
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) belum memiliki data supplier. Silakan isi data supplier terlebih dahulu.";
                        $errorCount++;
                        continue;
                    }

                    //ambil supp detail yang supp_utama = 1 
                    $supplierBarangDetail = SupplierBarangDetail::find()
                        ->where([
                            'supplier_barang_id' => $supplierBarang->supplier_barang_id,
                            'supp_utama' => 1
                        ])
                        ->one();
                    if (!$supplierBarangDetail) {
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) belum memiliki supplier utama (supp_utama = 1). Silakan tentukan supplier utama terlebih dahulu.";
                        $errorCount++;
                        continue;
                    }

                    $lead_time = $supplierBarangDetail->lead_time ?? 0;
                    $biayaPesan = $supplierBarangDetail->biaya_pesan ?? 0;

                    // validasi data yang dibutuhkan buat itung eoq dan rop
                    if ($biayaPesan <= 0) {
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) memiliki biaya pesan yang tidak valid (<= 0). Silakan periksa data supplier.";
                        $errorCount++;
                        continue;
                    }

                    if ($biayaSimpan <= 0) {
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) memiliki biaya simpan yang tidak valid (<= 0). Silakan periksa data barang.";
                        $errorCount++;
                        continue;
                    }

                    if ($demandSnapshot <= 0) {
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) memiliki demand yang tidak valid (<= 0). Silakan periksa data forecasting dan BOM.";
                        $errorCount++;
                        continue;
                    }

                    //hitung EOQ dan ROP
                    // rumus EOQ = sqrt((2DS)/H)
                    // D = demand per bulam, S = biaya simpan per bulan, H = biaya simpan per unit per bulan

                    $kategoriBarang = $barang->kategori_barang;

                    // Hitung berdasarkan kategori
                    if ($kategoriBarang == Barang::KATEGORI_FAST_MOVING) {
                        // FAST MOVING - Gunakan rumus EOQ dan ROP standar
                        // rumus EOQ = sqrt((2DS)/H)
                        $eoq = sqrt((2 * $demandSnapshot * $biayaPesan) / $biayaSimpan);
                        
                        // rumus ROP = (demand harian x lead time) + safety stock
                        $rop = (($demandSnapshot / 30) * $lead_time) + $safetyStock;
                        
                    } elseif ($kategoriBarang == Barang::KATEGORI_SLOW_MOVING) {
                        // SLOW MOVING - EOQ lebih kecil, ROP = Safety Stock
                        
                        // Opsi A: EOQ = 2 × Safety Stock (Recommended)
                        $eoq = $safetyStock * 2;
                        
                        // Opsi B: EOQ = Rata-rata demand 3 bulan
                        // $eoq = $demandSnapshot * 3;
                        
                        // ROP hanya safety stock (atau bisa tambah buffer kecil)
                        $rop = $safetyStock;
                        
                        // Alternatif ROP dengan buffer kecil:
                        // $rop = $safetyStock + (($demandSnapshot / 30) * $lead_time * 0.5);
                        
                    } elseif ($kategoriBarang == Barang::KATEGORI_NON_MOVING) {
                        // NON MOVING - Tidak perlu EOQ/ROP atau set ke 0
                        $eoq = 0;
                        $rop = 0;
                        
                        // Atau bisa skip generate sama sekali dengan continue
                        // continue;
                        
                    } else {
                        // Default fallback jika kategori tidak dikenali
                        $warningMessages[] = "Barang '{$namaBarang}' (ID: {$barangId}) memiliki kategori barang yang tidak valid.";
                        $errorCount++;
                        continue;
                    }

                    // Validasi hasil perhitungan
                    if ($eoq < 0) $eoq = 0;
                    if ($rop < 0) $rop = 0;

                    // cek apakah data untuk barang dan periode tersebut sudah ada
                    $existingEoqRop = EoqRop::find()
                        ->where([
                            'barang_id' => $barangId,
                            'periode' => $periode,
                        ])
                        ->one();

                    if ($existingEoqRop) {
                        //update data yang sudah ada
                        $model = $existingEoqRop;
                    } else {
                        // buat data baru
                        $model = new EoqRop();
                        $model->barang_id = $barangId;
                        $model->periode = $periode;
                    }

                    //set nilai snapshot dan hasil
                    $model->biaya_pesan_snapshot = $biayaPesan;
                    $model->biaya_simpan_snapshot = $biayaSimpan;
                    $model->safety_stock_snapshot = $safetyStock;
                    $model->lead_time_snapshot = $lead_time;
                    $model->demand_snapshot = $demandSnapshot;
                    $model->hasil_eoq = round($eoq, 2);
                    $model->hasil_rop = round($rop, 2);

                    if ($model->save()) {
                        $successCount++;
                    } else {
                        $errorCount++;
                        $errors[] = "Gagal simpan EOQ ROP untuk barang '{$namaBarang}' (ID: {$barangId}) pada periode '{$periode}'.";
                    }

                    //INI KALO GAK DIBEDAIN KATEGORINYA:
                    // $eoq = sqrt((2 * $demandSnapshot * $biayaPesan) / $biayaSimpan);

                    // // rumus ROP = (demand harian x lead time) + safety stock
                    // $rop = (($demandSnapshot / 30) * $lead_time) + $safetyStock;

                    // // cek apakah data untuk barang dan periode tersebut sudah ada
                    // $existingEoqRop = EoqRop::find()
                    //     ->where([
                    //         'barang_id' => $barangId,
                    //         'periode' => $periode,
                    //     ])
                    //     ->one();
                    // if ($existingEoqRop) {
                    //     //update data ang sudah ada
                    //     $model = $existingEoqRop;
                    // } else {
                    //     // buat data baru
                    //     $model = new EoqRop();
                    //     $model->barang_id = $barangId;
                    //     $model->periode = $periode;
                    // }

                    // //set nilai snapshot dan hasil
                    // $model->biaya_pesan_snapshot = $biayaPesan;
                    // $model->biaya_simpan_snapshot = $biayaSimpan;
                    // $model->safety_stock_snapshot = $safetyStock;
                    // $model->lead_time_snapshot = $lead_time;
                    // $model->demand_snapshot = $demandSnapshot;
                    // $model->hasil_eoq = round($eoq,2);
                    // $model->hasil_rop = round($rop, 2);

                    // if ($model->save()) {
                    //     $successCount++;
                    // } else {
                    //     $errorCount++;
                    //     $errors[] = "Gagal simpan EOQ ROP untuk barang '{$namaBarang}' (ID: {$barangId}) pada periode '{$periode}'.";
                    // }

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Error pada barang ID {$barangId}: " . $e->getMessage();
                }
            }
        }

        // set flash message hasil proses
        if ($successCount > 0) {
            Yii::$app->session->setFlash('success', "Berhasil generate EOQ ROP untuk {$successCount} item.");
        }

        if (!empty($warningMessages)) {
            // Gabungkan semua warning message
            $warningHtml = "<strong>Data tidak lengkap:</strong><br>" . implode('<br>', array_unique($warningMessages));
            Yii::$app->session->setFlash('warning', $warningHtml);
        }
        
        if ($errorCount > 0 && empty($warningMessages)) {
            Yii::$app->session->setFlash('error', "Gagal generate {$errorCount} data. Detail: " . implode(', ', array_slice($errors, 0, 3)));
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing EoqRop model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($EOQ_ROP_id)
    {
        $model = $this->findModel($EOQ_ROP_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'EOQ_ROP_id' => $model->EOQ_ROP_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EoqRop model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($EOQ_ROP_id)
    {
        $this->findModel($EOQ_ROP_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the EoqRop model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $EOQ_ROP_id Eoq Rop ID
     * @return EoqRop the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($EOQ_ROP_id)
    {
        if (($model = EoqRop::findOne(['EOQ_ROP_id' => $EOQ_ROP_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
