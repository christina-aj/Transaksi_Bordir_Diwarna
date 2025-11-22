<?php

namespace app\controllers;

use app\models\EoqRop;
use app\models\EoqRopHistory;
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

        $canGenerate = $this->canGenerateEoqRop();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canGenerate' => $canGenerate,
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
        if (!$this->canGenerateEoqRop()) {
            Yii::$app->session->setFlash('warning', 'EOQ/ROP sudah digenerate untuk periode forecast aktif.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            // Validasi awal
            $baseProduct = $this->validateBaseProduct();
            $baseProductId = $baseProduct->barang_produksi_id;
            
            $forecastPeriodes = $this->validateForecast();
            $periodeLabel = $this->formatPeriodeLabel($forecastPeriodes);

            // Ambil data BOM dan Slow Moving
            $bomData = $this->getBomData($baseProductId);
            $this->validateBomNotEmpty($bomData, $baseProduct->nama);
            
            $demandData = $this->mergeBomAndSlowMoving($bomData);

            // Proses generate
            $result = $this->processEoqRopGeneration($demandData, $periodeLabel);

            // Validasi hasil
            $this->validateGenerationResult($result, count($bomData));

            $transaction->commit();

            Yii::$app->session->setFlash('success', "Berhasil generate EOQ ROP untuk {$result['successCount']} bahan baku (periode {$periodeLabel}).");

        } catch (\Exception $e) {
            $transaction->rollBack();
            
            $errorMsg = "❌ Generate EOQ/ROP dibatalkan. " . $e->getMessage();
            $errorMsg .= "<br><br><strong>Tidak ada data yang tersimpan.</strong> Silakan lengkapi semua data yang diperlukan (pastikan barang base kaus kaki mempunyai bom dan masing-masing bom mempunyai supplier";
            
            Yii::error("Generate EOQ/ROP failed: " . $e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', $errorMsg);
        }

        return $this->redirect(['index']);
    }

    /**
     * Validasi base product
     * @return \app\models\BarangProduksi
     * @throws \Exception
     */
    private function validateBaseProduct()
    {
        $baseProduct = \app\models\BarangProduksi::find()
            ->where(['nama_jenis' => 'Base'])
            ->one();
        
        if (!$baseProduct) {
            throw new \Exception('Base product tidak ditemukan. Pastikan ada data di barang_produksi dengan nama_jenis = "Base".');
        }
        
        return $baseProduct;
    }

    /**
     * Validasi forecast
     * @return array
     * @throws \Exception
     */
    private function validateForecast()
    {
        $forecastPeriodes = Forecast::find()
            ->select('periode_forecast')
            ->distinct()
            ->orderBy(['periode_forecast' => SORT_ASC])
            ->column();
            
        if (empty($forecastPeriodes)) {
            throw new \Exception('Tidak ada data forecasting. Silakan lakukan forecasting terlebih dahulu.');
        }
        
        return $forecastPeriodes;
    }

    /**
     * Format periode label
     * @param array $periodes
     * @return string
     */
    private function formatPeriodeLabel($periodes)
    {
        $periodeAwal = $periodes[0];
        $periodeAkhir = $periodes[count($periodes) - 1];
        return $periodeAwal . '-' . $periodeAkhir;
    }

    /**
     * Ambil data BOM
     * @param int $baseProductId
     * @return array
     */
    private function getBomData($baseProductId)
    {
        $sql = "
            SELECT
                bd.barang_id,
                SUM(f.hasil_forecast * bd.qty_BOM) AS demand_snapshot,
                'from_bom' as source
            FROM forecast f
            INNER JOIN BOM_barang bb ON bb.barang_produksi_id = :baseProductId
            INNER JOIN BOM_detail bd ON bb.BOM_barang_id = bd.BOM_barang_id
            GROUP BY bd.barang_id
        ";

        return Yii::$app->db->createCommand($sql)
            ->bindValue(':baseProductId', $baseProductId)
            ->queryAll();
    }

    /**
     * Validasi BOM tidak kosong
     * @param array $bomData
     * @param string $productName
     * @throws \Exception
     */
    private function validateBomNotEmpty($bomData, $productName)
    {
        if (empty($bomData)) {
            throw new \Exception('BOM untuk base product "' . $productName . '" kosong. Silakan isi BOM detail terlebih dahulu.');
        }
    }

    /**
     * Gabungkan BOM dan Slow Moving
     * @param array $bomData
     * @return array
     */
    private function mergeBomAndSlowMoving($bomData)
    {
        $bomBarangIds = array_column($bomData, 'barang_id');
        
        $nonBomBarangs = Barang::find()
            ->where(['kategori_barang' => [Barang::KATEGORI_SLOW_MOVING]])
            ->andWhere(['jenis_barang' => 1])
            ->andWhere(['NOT IN', 'barang_id', $bomBarangIds ?: [0]])
            ->all();

        $demandData = $bomData;
        foreach ($nonBomBarangs as $barang) {
            $demandData[] = [
                'barang_id' => $barang->barang_id,
                'demand_snapshot' => 0,
                'source' => 'non_bom'
            ];
        }

        return $demandData;
    }

    /**
     * Proses generate EOQ ROP untuk semua barang
     * @param array $demandData
     * @param string $periodeLabel
     * @return array
     */
    private function processEoqRopGeneration($demandData, $periodeLabel)
    {
        $successCount = 0;
        $allErrors = [];
        $bomSuccessIds = [];

        foreach ($demandData as $data) {
            $barangId = $data['barang_id'];
            $source = $data['source'];

            // Validasi barang
            $validationResult = $this->validateBarangForEoqRop($barangId, $data, $source);
            
            if (!$validationResult['valid']) {
                $allErrors = array_merge($allErrors, $validationResult['errors']);
                continue;
            }

            // Hitung EOQ ROP
            $eoqRopData = $this->calculateEoqRop(
                $validationResult['barang'],
                $validationResult['supplierDetail'],
                $data['demand_snapshot'],
                $source
            );

            // Simpan
            if ($this->saveEoqRop($barangId, $periodeLabel, $eoqRopData, $validationResult)) {
                $successCount++;
                if ($source == 'from_bom') {
                    $bomSuccessIds[] = $barangId;
                }
            } else {
                $allErrors[] = "Gagal simpan EOQ ROP untuk barang ID {$barangId}.";
            }
        }

        // Throw jika ada error
        if (!empty($allErrors)) {
            throw new \Exception("Generate dibatalkan karena ada " . count($allErrors) . " barang dengan data tidak lengkap:<br><br>" . implode('<br>', $allErrors));
        }

        return [
            'successCount' => $successCount,
            'bomSuccessIds' => $bomSuccessIds
        ];
    }

    /**
     * Validasi barang untuk EOQ ROP (SEMUA VALIDASI DISINI)
     * @param int $barangId
     * @param array $data
     * @param string $source
     * @return array
     */
    private function validateBarangForEoqRop($barangId, $data, $source)
    {
        $errors = [];

        // 1. Validasi barang ada
        $barang = Barang::findOne($barangId);
        if (!$barang) {
            return ['valid' => false, 'errors' => ["Barang ID {$barangId} tidak ditemukan."]];
        }

        $namaBarang = $barang->nama_barang;

        // 2. Validasi supplier
        $supplierBarang = SupplierBarang::find()->where(['barang_id' => $barangId])->one();
        if (!$supplierBarang) {
            $errors[] = "Barang '{$namaBarang}' (ID: {$barangId}) belum memiliki data supplier.";
        }

        // 3. Validasi supplier utama
        $supplierDetail = null;
        if ($supplierBarang) {
            $supplierDetail = SupplierBarangDetail::find()
                ->where(['supplier_barang_id' => $supplierBarang->supplier_barang_id, 'supp_utama' => 1])
                ->one();
            
            if (!$supplierDetail) {
                $errors[] = "Barang '{$namaBarang}' (ID: {$barangId}) belum memiliki supplier utama.";
            }
        }

        // 4. Validasi biaya
        if ($supplierDetail) {
            if (($supplierDetail->biaya_pesan ?? 0) <= 0) {
                $errors[] = "Barang '{$namaBarang}' memiliki biaya pesan tidak valid.";
            }
        }

        if (($barang->biaya_simpan_bulan ?? 0) <= 0) {
            $errors[] = "Barang '{$namaBarang}' memiliki biaya simpan tidak valid.";
        }

        // 5. Validasi demand untuk BOM
        if ($source == 'from_bom' && $data['demand_snapshot'] <= 0) {
            $errors[] = "Barang '{$namaBarang}' memiliki demand tidak valid.";
        }

        // 6. Validasi safety stock untuk slow moving
        if ($source == 'non_bom' && ($barang->safety_stock ?? 0) <= 0) {
            $errors[] = "Barang '{$namaBarang}' (Slow Moving) harus memiliki Safety Stock > 0.";
        }

        if (!empty($errors)) {
            return ['valid' => false, 'errors' => $errors];
        }

        return [
            'valid' => true,
            'barang' => $barang,
            'supplierDetail' => $supplierDetail,
            'errors' => []
        ];
    }

    /**
     * Hitung EOQ dan ROP
     * @param Barang $barang
     * @param SupplierBarangDetail $supplierDetail
     * @param float $demand
     * @param string $source
     * @return array
     */
    private function calculateEoqRop($barang, $supplierDetail, $demand, $source)
    {
        $safetyStock = $barang->safety_stock ?? 0;
        $biayaSimpan = $barang->biaya_simpan_bulan;
        $biayaPesan = $supplierDetail->biaya_pesan;
        $lead_time = $supplierDetail->lead_time ?? 0;

        if ($source == 'from_bom') {
            if ($barang->kategori_barang == Barang::KATEGORI_FAST_MOVING) {
                $eoq = sqrt((2 * $demand * $biayaPesan) / $biayaSimpan);
                $rop = (($demand / 120) * $lead_time) + $safetyStock;
            } else {
                $eoq = $safetyStock * 2;
                $rop = $safetyStock;
            }
        } else {
            $demand = 0;
            $rop = $safetyStock;
            $eoq = $rop * 2;
        }

        $totalBiaya = 0;
        if ($eoq > 0 && $demand > 0) {
            $totalBiaya = ($demand / $eoq) * $biayaPesan + ($eoq / 2) * $biayaSimpan;
        }

        return [
            'eoq' => round($eoq, 2),
            'rop' => round($rop, 2),
            'demand' => $demand,
            'biaya_pesan' => $biayaPesan,
            'biaya_simpan' => $biayaSimpan,
            'safety_stock' => $safetyStock,
            'lead_time' => $lead_time,
            'total_biaya' => round($totalBiaya, 2)
        ];
    }

    /**
     * Simpan EOQ ROP dan History
     * @param int $barangId
     * @param string $periodeLabel
     * @param array $eoqRopData
     * @param array $validationResult
     * @return bool
     */
    private function saveEoqRop($barangId, $periodeLabel, $eoqRopData, $validationResult)
    {
        $existing = EoqRop::find()->where(['barang_id' => $barangId, 'periode' => $periodeLabel])->one();
        
        $model = $existing ?: new EoqRop();
        $model->barang_id = $barangId;
        $model->periode = $periodeLabel;
        $model->biaya_pesan_snapshot = $eoqRopData['biaya_pesan'];
        $model->biaya_simpan_snapshot = $eoqRopData['biaya_simpan'];
        $model->safety_stock_snapshot = $eoqRopData['safety_stock'];
        $model->lead_time_snapshot = $eoqRopData['lead_time'];
        $model->demand_snapshot = $eoqRopData['demand'];
        $model->hasil_eoq = $eoqRopData['eoq'];
        $model->hasil_rop = $eoqRopData['rop'];
        $model->total_biaya_persediaan = $eoqRopData['total_biaya'];
        $model->created_at = date('Y-m-d H:i:s');

        if (!$model->save()) {
            return false;
        }

        // Simpan history
        $history = new EoqRopHistory();
        $history->barang_id = $barangId;
        $history->periode = $periodeLabel;
        $history->biaya_pesan_snapshot = $eoqRopData['biaya_pesan'];
        $history->biaya_simpan_snapshot = $eoqRopData['biaya_simpan'];
        $history->safety_stock_snapshot = $eoqRopData['safety_stock'];
        $history->lead_time_snapshot = $eoqRopData['lead_time'];
        $history->demand_snapshot = $eoqRopData['demand'];
        $history->hasil_eoq = $eoqRopData['eoq'];
        $history->hasil_rop = $eoqRopData['rop'];
        $history->total_biaya_persediaan = $eoqRopData['total_biaya'];
        $history->created_at = date('Y-m-d H:i:s');
        
        return $history->save(false);
    }

    /**
     * Validasi hasil generation
     * @param array $result
     * @param int $totalBomItems
     * @throws \Exception
     */
    private function validateGenerationResult($result, $totalBomItems)
    {
        $bomSuccessCount = count($result['bomSuccessIds']);
        
        if ($bomSuccessCount < $totalBomItems) {
            $missing = $totalBomItems - $bomSuccessCount;
            throw new \Exception("Hanya {$bomSuccessCount} dari {$totalBomItems} bahan baku di BOM yang berhasil. {$missing} bahan baku gagal.");
        }
        
        if ($result['successCount'] == 0) {
            throw new \Exception('Tidak ada barang yang berhasil digenerate.');
        }
    }

    /**
     * Updates an existing EoqRop model.
     * @param int $EOQ_ROP_id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($EOQ_ROP_id)
    {
        $model = $this->findModel($EOQ_ROP_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'EOQ_ROP_id' => $model->EOQ_ROP_id]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing EoqRop model.
     * @param int $EOQ_ROP_id
     * @return \yii\web\Response
     */
    public function actionDelete($EOQ_ROP_id)
    {
        $this->findModel($EOQ_ROP_id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Update data aktual untuk EOQ ROP History
     */
    public function actionUpdateActual()
    {
        $periodeList = EoqRopHistory::find()->select('periode')->distinct()->column();

        foreach ($periodeList as $periode) {
            EoqRopHistory::updateDataAktual($periode);
        }

        Yii::$app->session->setFlash('success', 'Data aktual berhasil diperbarui.');
        return $this->redirect(['history/index']);
    }

    /**
     * Cek apakah bisa generate EOQ ROP baru
     * @return bool
     */
    private function canGenerateEoqRop()
    {
        $forecastPeriodes = Forecast::find()
            ->select('periode_forecast')
            ->distinct()
            ->orderBy(['periode_forecast' => SORT_ASC])
            ->column();
            
        if (empty($forecastPeriodes)) {
            return false;
        }

        $periodeLabel = $this->formatPeriodeLabel($forecastPeriodes);
        return !EoqRop::find()->where(['periode' => $periodeLabel])->exists();
    }

    /**
     * Finds the EoqRop model based on its primary key value.
     * @param int $EOQ_ROP_id
     * @return EoqRop
     * @throws NotFoundHttpException
     */
    protected function findModel($EOQ_ROP_id)
    {
        if (($model = EoqRop::findOne(['EOQ_ROP_id' => $EOQ_ROP_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}