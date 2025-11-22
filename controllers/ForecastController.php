<?php

namespace app\controllers;

use app\models\Forecast;
use app\models\ForecastHistory;
use app\models\ForecastSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

class ForecastController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'create' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Forecast models.
     */
    public function actionIndex()
    {
        $searchModel = new ForecastSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        // Cek apakah bisa generate forecast baru
        $canGenerate = Forecast::canGenerateNewForecast();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'canGenerate' => $canGenerate,
        ]);
    }

    /**
     * Generate forecast untuk beberapa bulan ke depan menggunakan Triple Exponential Smoothing (Holt-Winters additive)
     */
    public function actionCreate()
    {
        // Cek apakah bisa generate
        if (!Forecast::canGenerateNewForecast()) {
            Yii::$app->session->setFlash('warning', 'Forecast hanya bisa digenerate setiap 4 bulan. Tunggu hingga bulan ke-4 selesai.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // Ambil 24 bulan terakhir aggregated per bulan (jika mau semua data, hilangkan ->limit(24))
            // $historicalData = (new \yii\db\Query())
            //     ->select(['bulan_periode', 'SUM(qty_penjualan) AS total'])
            //     ->from('riwayat_penjualan')
            //     ->groupBy('bulan_periode')
            //     ->orderBy(['bulan_periode' => SORT_DESC]) // ambil terbaru dulu
            //     ->limit(24)
            //     ->all(Yii::$app->db);

            // // balik urutan jadi ASC (dari yang paling lama ke paling baru)
            // $historicalData = array_reverse($historicalData);

            $historicalData = (new \yii\db\Query())
                ->select(['bulan_periode', 'SUM(qty_penjualan) AS total'])
                ->from('riwayat_penjualan')
                ->groupBy('bulan_periode')
                ->orderBy(['bulan_periode' => SORT_ASC]) // harus ASC
                ->all(Yii::$app->db);

            // Ambil 24 bulan terakhir (optional)
            $historicalData = array_slice($historicalData, -24);

            // cast total jadi integer
            foreach ($historicalData as &$row) {
                $row['total'] = (int)$row['total'];
            }
            unset($row);

            // validasi minimal 24 periode (2 musim) untuk TES seasonal
            $seasonalPeriod = 12;
            if (count($historicalData) < 2 * $seasonalPeriod) {
                Yii::$app->session->setFlash('error', 'Data historis kurang. Minimal ' . (2 * $seasonalPeriod) . ' bulan (2 tahun) diperlukan untuk TES seasonal.');
                return $this->redirect(['index']);
            }

            // Jalankan Triple Exponential Smoothing dengan grid-search parameter
            $forecastHorizon = 4; // 4 bulan ke depan
            $result = $this->tripleExponentialSmoothing($historicalData, $forecastHorizon, $seasonalPeriod);

            // Simpan hasil forecast ke database
            $savedCount = 0;
            foreach ($result['forecasts'] as $fc) {
                // Cek apakah forecast untuk periode ini sudah ada
                $existing = Forecast::findOne(['periode_forecast' => $fc['periode']]);
                if ($existing) {
                    continue; // Skip jika sudah ada
                }

                $model = new Forecast();
                $model->barang_produksi_id = null;
                $model->barang_custom_pelanggan_id = null;
                $model->periode_forecast = $fc['periode'];
                $model->nilai_alpha = $result['alpha'];
                $model->nilai_beta = $result['beta'];
                $model->nilai_gamma = $result['gamma'];
                $model->mape_test = $result['mape'];
                $model->hasil_forecast = $fc['nilai'];
                $model->seasonal_period = $seasonalPeriod;
                $model->created_at = date('Y-m-d H:i:s');

                if ($model->save()) {
                    $savedCount++;

                    // Juga simpan ke history untuk tracking parameter
                    $history = new ForecastHistory();
                    $history->periode_forecast = $fc['periode'];
                    $history->hasil_forecast = $fc['nilai'];
                    $history->nilai_alpha = $result['alpha'];
                    $history->nilai_beta = $result['beta'];
                    $history->nilai_gamma = $result['gamma'];
                    $history->mape_test = $result['mape'];
                    $history->seasonal_period = $seasonalPeriod;
                    $history->tanggal_dibuat = date('Y-m-d H:i:s');
                    $history->save(false);
                }
            }

            $transaction->commit();

            if ($savedCount > 0) {
                Yii::$app->session->setFlash('success', "Berhasil generate forecast untuk {$savedCount} bulan ke depan. MAPE: {$result['mape']}%, Alpha: {$result['alpha']}, Beta: {$result['beta']}, Gamma: {$result['gamma']}");
            } else {
                Yii::$app->session->setFlash('info', 'Forecast untuk periode ini sudah ada.');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Forecast model.
     */
    public function actionDelete($forecast_id)
    {
        $this->findModel($forecast_id)->delete();
        Yii::$app->session->setFlash('success', 'Forecast berhasil dihapus.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Forecast model based on its primary key value.
     */
    protected function findModel($id)
    {
        if (($model = Forecast::findOne(['forecast_id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Action untuk update data aktual (dipanggil otomatis atau manual)
     */
    public function actionUpdateActual()
    {
        // Ambil semua periode yang ada di tabel FORECAST
        $periodeList = Forecast::find()
            ->select('periode_forecast')
            ->column(); // return array [202511, 202512, 202601, ...]

        foreach ($periodeList as $periode) {
            ForecastHistory::updateDataAktual($periode);
        }

        Yii::$app->session->setFlash('success', 'Data aktual berhasil diperbarui untuk semua periode forecast.');
        return $this->redirect(['forecast-history/index']);
    }

    /**
     * Triple Exponential Smoothing controller wrapper (perbaikan indexing periode)
     *
     * @param array $data [['bulan_periode'=>202301, 'total'=>...], ...] (ASC order)
     * @param int $forecastHorizon
     * @param int $seasonalPeriod
     * @return array
     */
    private function tripleExponentialSmoothing($data, $forecastHorizon = 4, $seasonalPeriod = 12)
    {
        $n = count($data);
        $values = array_column($data, 'total');

        // DEBUG: Log data input
        Yii::info("=== TRIPLE EXPONENTIAL SMOOTHING DEBUG ===", __METHOD__);
        Yii::info("Total data: {$n} bulan", __METHOD__);
        Yii::info("Seasonal period: {$seasonalPeriod}", __METHOD__);
        Yii::info("Forecast horizon: {$forecastHorizon}", __METHOD__);
        
        // Validasi: Data harus >= 2 * seasonal period
        if ($n < 2 * $seasonalPeriod) {
            Yii::error("Data tidak cukup! Butuh minimal " . (2 * $seasonalPeriod) . " bulan, tersedia: {$n} bulan", __METHOD__);
            throw new \Exception("Data tidak cukup untuk Triple Exponential Smoothing. Minimal " . (2 * $seasonalPeriod) . " bulan data diperlukan.");
        }
        
        // Grid search untuk parameter optimal
        $bestMape = PHP_FLOAT_MAX;
        $bestParams = ['alpha' => 0.3, 'beta' => 0.1, 'gamma' => 0.1];
        $testCount = 0;
        
        Yii::info("\n=== GRID SEARCH START ===", __METHOD__);
        
        // // Coba berbagai kombinasi parameter
        // for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.2) {
        //     for ($beta = 0.1; $beta <= 0.5; $beta += 0.2) {
        //         for ($gamma = 0.1; $gamma <= 0.5; $gamma += 0.2) {
        // Grid search yang lebih halus dan range lebih luas
        for ($alpha = 0.05; $alpha <= 0.95; $alpha += 0.1) {  // 10 nilai
            for ($beta = 0.05; $beta <= 0.95; $beta += 0.1) {  // 10 nilai
                for ($gamma = 0.05; $gamma <= 0.95; $gamma += 0.1) { // 10 nilai
                    $testCount++;
                    
                    try {
                        $result = $this->calculateTES($values, $alpha, $beta, $gamma, $seasonalPeriod);
                        
                        Yii::info("Test #{$testCount}: α={$alpha}, β={$beta}, γ={$gamma} → MAPE={$result['mape']}%", __METHOD__);
                        
                        if ($result['mape'] < $bestMape && !is_nan($result['mape']) && !is_infinite($result['mape'])) {
                            $bestMape = $result['mape'];
                            $bestParams = ['alpha' => $alpha, 'beta' => $beta, 'gamma' => $gamma];
                            Yii::info("  ✓ NEW BEST! MAPE={$bestMape}%", __METHOD__);
                        }
                    } catch (\Exception $e) {
                        Yii::warning("Test #{$testCount} FAILED: " . $e->getMessage(), __METHOD__);
                    }
                }
            }
        }
        
        Yii::info("\n=== BEST PARAMETERS ===", __METHOD__);
        Yii::info("Alpha: {$bestParams['alpha']}", __METHOD__);
        Yii::info("Beta: {$bestParams['beta']}", __METHOD__);
        Yii::info("Gamma: {$bestParams['gamma']}", __METHOD__);
        Yii::info("MAPE: {$bestMape}%", __METHOD__);

        // ===== HAPUS SEMUA KODE GRID SEARCH YANG DI-COMMENT DI BAWAH INI =====
        // JANGAN ADA DUPLIKAT GRID SEARCH!
        
        // Hitung TES dengan parameter terbaik
        $result = $this->calculateTES($values, $bestParams['alpha'], $bestParams['beta'], $bestParams['gamma'], $seasonalPeriod);

        // Ambil periode terakhir dari key 'bulan_periode'
        if (!isset($data[$n - 1]['bulan_periode'])) {
            throw new \Exception("Key 'bulan_periode' tidak ditemukan pada data historis terakhir.");
        }
        $lastPeriode = (int)$data[$n - 1]['bulan_periode'];

        $forecasts = [];

        Yii::info("\n=== GENERATE FORECAST ===", __METHOD__);

        for ($h = 1; $h <= $forecastHorizon; $h++) {
            $nextPeriode = $lastPeriode;
            for ($j = 0; $j < $h; $j++) {
                $nextPeriode = Forecast::getNextPeriode($nextPeriode);
            }

            $seasonalIndex = ($n + ($h - 1)) % $seasonalPeriod;
            $forecastValue = $result['level'] + ($h * $result['trend']) + $result['seasonal'][$seasonalIndex];

            $forecasts[] = [
                'periode' => $nextPeriode,
                'nilai' => max(0, (int) round($forecastValue))
            ];
        }

        // ===== PENTING: PASTIKAN RETURN INI BENAR =====
        $finalResult = [
            'forecasts' => $forecasts,
            'alpha' => round($bestParams['alpha'], 4),
            'beta' => round($bestParams['beta'], 4),
            'gamma' => round($bestParams['gamma'], 4),
            'mape' => round($bestMape, 2)  // <-- GUNAKAN $bestMape, BUKAN $result['mape']
        ];
        
        Yii::info("FINAL RETURN: MAPE = {$finalResult['mape']}%", __METHOD__);
        
        return $finalResult;
    }


    /**
     * Kalkulasi Triple Exponential Smoothing (Holt-Winters additive) dengan inisialisasi seasonal yang aman
     *
     * @param array $values numeric array [v0, v1, ...] dalam urutan waktu (ASC)
     * @param float $alpha
     * @param float $beta
     * @param float $gamma
     * @param int $seasonalPeriod
     * @return array ['level'=>..., 'trend'=>..., 'seasonal'=>[...], 'mape'=>...]
     * @throws \Exception jika data tidak cukup
     */
    private function calculateTES($values, $alpha, $beta, $gamma, $seasonalPeriod)
    {
        $n = count($values);

        // Pastikan cukup data: minimal 2 musim penuh
        $minNeeded = 2 * $seasonalPeriod;
        if ($n < $minNeeded) {
            throw new \Exception("Data historis minimal {$minNeeded} periode diperlukan untuk TES seasonal. Saat ini: {$n}");
        }

        // Inisialisasi seasonal menggunakan rata-rata musim (seasonal averages)
        $numSeasons = floor($n / $seasonalPeriod);

        // 1) Hitung seasonal averages (rata-rata tiap posisi musim)
        $seasonAverages = array_fill(0, $seasonalPeriod, 0.0);
        for ($i = 0; $i < $seasonalPeriod; $i++) {
            $sum = 0.0;
            for ($m = 0; $m < $numSeasons; $m++) {
                $idx = $i + $m * $seasonalPeriod;
                if (isset($values[$idx])) {
                    $sum += $values[$idx];
                }
            }
            $seasonAverages[$i] = $sum / $numSeasons;
        }

        // 2) Initial level = rata-rata periode pertama (first season)
        $level = array_sum(array_slice($values, 0, $seasonalPeriod)) / $seasonalPeriod;

        // 3) Initial trend = rata-rata per posisi ((season2 - season1) / seasonalPeriod)
        $sumTrend = 0.0;
        for ($i = 0; $i < $seasonalPeriod; $i++) {
            $idx1 = $i;
            $idx2 = $i + $seasonalPeriod;
            if (isset($values[$idx1]) && isset($values[$idx2])) {
                $sumTrend += ($values[$idx2] - $values[$idx1]) / $seasonalPeriod;
            }
        }
        $trend = $sumTrend / $seasonalPeriod;

        // 4) Initial seasonal indices = value(i) - seasonAverages(i)
        // $seasonal = array_fill(0, $seasonalPeriod, 0.0);
        // for ($i = 0; $i < $seasonalPeriod; $i++) {
        //     if (!isset($values[$i])) {
        //         throw new \Exception("Index awal seasonal tidak ditemukan untuk i={$i}");
        //     }
        //     $seasonal[$i] = $values[$i] - $seasonAverages[$i];
        // }

        // 4) Initial seasonal indices menggunakan metode yang lebih robust
        $overallAvg = array_sum($values) / $n;
        $seasonal = array_fill(0, $seasonalPeriod, 0.0);

        for ($i = 0; $i < $seasonalPeriod; $i++) {
            $sum = 0.0;
            $count = 0;
            
            // Ambil rata-rata dari semua nilai pada posisi musim yang sama
            for ($m = 0; $m < $numSeasons; $m++) {
                $idx = $i + $m * $seasonalPeriod;
                if (isset($values[$idx])) {
                    $sum += $values[$idx];
                    $count++;
                }
            }
            
            $avgForSeason = $count > 0 ? $sum / $count : $overallAvg;
            $seasonal[$i] = $avgForSeason - $overallAvg; // Seasonal component
        }

        // Normalisasi seasonal agar sum = 0
        $seasonalSum = array_sum($seasonal);
        for ($i = 0; $i < $seasonalPeriod; $i++) {
            $seasonal[$i] -= $seasonalSum / $seasonalPeriod;
        }

        // Simpan fitted values DAN actual values untuk MAPE
        $fittedValues = [];
        $actualValues = [];
        
        for ($t = $seasonalPeriod; $t < $n; $t++) {
            $prevLevel = $level;
            $prevTrend = $trend;
            $seasonIdx = $t % $seasonalPeriod;

            // Hitung fitted value SEBELUM update (one-step-ahead forecast)
            $fittedVal = $prevLevel + $prevTrend + $seasonal[$seasonIdx];
            $fittedValues[] = $fittedVal;
            $actualValues[] = $values[$t];

            // Update level
            $level = $alpha * ($values[$t] - $seasonal[$seasonIdx]) + (1 - $alpha) * ($prevLevel + $prevTrend);

            // Update trend
            $trend = $beta * ($level - $prevLevel) + (1 - $beta) * $prevTrend;

            // Update seasonal
            $seasonal[$seasonIdx] = $gamma * ($values[$t] - $level) + (1 - $gamma) * $seasonal[$seasonIdx];
        }

        // Hitung MAPE dengan array yang sudah aligned
        $mape = $this->calculateMAPE($actualValues, $fittedValues);

        return [
            'level' => $level,
            'trend' => $trend,
            'seasonal' => $seasonal,
            'mape' => $mape
        ];
    }

    /**
     * Hitung MAPE (Mean Absolute Percentage Error)
     */
    private function calculateMAPE($actual, $forecast)
    {
        $n = count($actual);
        
        // Validasi array tidak kosong dan ukuran sama
        if ($n === 0 || count($forecast) === 0) {
            Yii::warning("MAPE: Array kosong! actual={$n}, forecast=" . count($forecast), __METHOD__);
            return PHP_FLOAT_MAX;
        }
        
        if ($n !== count($forecast)) {
            Yii::warning("MAPE: Ukuran array tidak sama! actual={$n}, forecast=" . count($forecast), __METHOD__);
            return PHP_FLOAT_MAX;
        }

        $sumPercentageError = 0.0;
        $validCount = 0;

        for ($i = 0; $i < $n; $i++) {
            // Skip jika actual = 0 (pembagian dengan nol)
            if ($actual[$i] == 0) {
                continue;
            }

            $percentageError = abs(($actual[$i] - $forecast[$i]) / $actual[$i]);
            
            // Skip jika hasilnya NaN atau Infinite
            if (is_nan($percentageError) || is_infinite($percentageError)) {
                continue;
            }
            
            $sumPercentageError += $percentageError;
            $validCount++;
        }

        if ($validCount === 0) {
            Yii::warning("MAPE: Tidak ada data valid untuk dihitung", __METHOD__);
            return PHP_FLOAT_MAX;
        }

        return ($sumPercentageError / $validCount) * 100;
    }
}
