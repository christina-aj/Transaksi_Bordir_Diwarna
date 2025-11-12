<?php

namespace app\controllers;

use app\models\Forecast;
use app\models\ForecastSearch;
use app\models\RiwayatPenjualan;
use app\models\BarangProduksi;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use app\models\ForecastHistory;

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
                    ],
                ],
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new ForecastSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($forecast_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($forecast_id),
        ]);
    }

    // public function actionCreate()
    // {
    //     try {
    //         $barangList = BarangProduksi::find()->all();
            
    //         if (empty($barangList)) {
    //             Yii::$app->session->setFlash('error', 'Tidak ada data barang produksi.');
    //             return $this->redirect(['index']);
    //         }

    //         $successCount = 0;
    //         $errorCount = 0;
    //         $skippedCount = 0;
    //         $errorMessages = [];
    //         $today = date('Y-m-d');

    //         foreach ($barangList as $barang) {
    //             Yii::info("\n" . str_repeat("=", 80), __METHOD__);
    //             Yii::info("MEMPROSES BARANG: {$barang->nama} (ID: {$barang->barang_produksi_id})", __METHOD__);
    //             Yii::info(str_repeat("=", 80), __METHOD__);

    //             // Ambil data riwayat penjualan untuk barang ini
    //             $riwayatData = RiwayatPenjualan::find()
    //                 ->select([
    //                     'bulan_periode',
    //                     'SUM(qty_penjualan) as total_qty'
    //                 ])
    //                 ->where(['barang_produksi_id' => $barang->barang_produksi_id])
    //                 ->groupBy('bulan_periode')
    //                 ->orderBy(['bulan_periode' => SORT_ASC])
    //                 ->asArray()
    //                 ->all();

    //             // Skip jika tidak ada riwayat penjualan
    //             if (empty($riwayatData)) {
    //                 Yii::info("⚠ Tidak ada data riwayat penjualan\n", __METHOD__);
    //                 $skippedCount++;
    //                 continue;
    //             }

    //             // Cek apakah data sudah 12 bulan (1 tahun)
    //             if (count($riwayatData) < 12) {
    //                 $errorMessages[] = "Barang '{$barang->nama}' belum memiliki data 12 bulan (hanya " . count($riwayatData) . " bulan). Dilewati.";
    //                 Yii::info("⚠ Data kurang dari 12 bulan: " . count($riwayatData) . " bulan\n", __METHOD__);
    //                 $skippedCount++;
    //                 continue;
    //             }

    //             // Ambil data penjualan untuk perhitungan
    //             $salesData = [];
    //             foreach ($riwayatData as $riwayat) {
    //                 $salesData[] = [
    //                     'periode' => $riwayat['bulan_periode'],
    //                     'qty' => $riwayat['total_qty']
    //                 ];
    //             }

    //             // Tampilkan data aktual
    //             Yii::info("\nDATA AKTUAL PENJUALAN:", __METHOD__);
    //             foreach ($salesData as $idx => $data) {
    //                 Yii::info("Periode " . ($idx + 1) . " ({$data['periode']}): A = {$data['qty']}", __METHOD__);
    //             }

    //             // Tentukan periode yang mau di-forecast
    //             $lastPeriode = $salesData[count($salesData) - 1]['periode'];
    //             $nextPeriode = Forecast::getNextPeriode($lastPeriode);
                
    //             Yii::info("\nPeriode terakhir: {$lastPeriode}", __METHOD__);
    //             Yii::info("Periode forecast: {$nextPeriode}\n", __METHOD__);

    //             // CEK: Apakah forecast untuk periode ini sudah pernah dibuat?
    //             $existingHistory = ForecastHistory::find()
    //                 ->where([
    //                     'barang_produksi_id' => $barang->barang_produksi_id,
    //                     'periode_forecast' => $nextPeriode
    //                 ])
    //                 ->one();
                
    //             // JIKA SUDAH PERNAH DIBUAT, SKIP!
    //             if ($existingHistory) {
    //                 Yii::info("⚠ Forecast untuk periode {$nextPeriode} sudah ada. Dilewati.\n", __METHOD__);
    //                 $skippedCount++;
    //                 continue;
    //             }

    //             // Hitung alpha terbaik dan MAPE dengan detail log
    //             $bestResult = $this->findBestAlphaWithDetail($salesData, $barang->nama);

    //             if ($bestResult === false) {
    //                 $errorMessages[] = "Gagal menghitung forecast untuk barang '{$barang->nama}'";
    //                 $errorCount++;
    //                 continue;
    //             }

    //             // Hitung forecast untuk periode berikutnya
    //             $nextForecast = $this->calculateNextPeriodForecast($salesData, $bestResult['alpha']);

    //             Yii::info("\n" . str_repeat("-", 80), __METHOD__);
    //             Yii::info("HASIL AKHIR:", __METHOD__);
    //             Yii::info("Alpha terbaik: {$bestResult['alpha']}", __METHOD__);
    //             Yii::info("MAPE: {$bestResult['mape']}%", __METHOD__);
    //             Yii::info("Forecast periode {$nextPeriode}: " . round($nextForecast) . " unit", __METHOD__);
    //             Yii::info(str_repeat("-", 80) . "\n", __METHOD__);

    //             // SIMPAN KE HISTORY (HANYA SEKALI!)
    //             $forecastHistory = new ForecastHistory();
    //             $forecastHistory->barang_produksi_id = $barang->barang_produksi_id;
    //             $forecastHistory->periode_forecast = $nextPeriode;
    //             $forecastHistory->tanggal_dibuat = $today;
    //             $forecastHistory->nilai_alpha = $bestResult['alpha'];
    //             $forecastHistory->mape_test = $bestResult['mape'];
    //             $forecastHistory->hasil_forecast = $nextForecast;
    //             $forecastHistory->data_aktual = null;
                
    //             if (!$forecastHistory->save()) {
    //                 $errors = implode(', ', $forecastHistory->getErrorSummary(true));
    //                 $errorMessages[] = "Gagal menyimpan history forecast untuk '{$barang->nama}': {$errors}";
    //                 $errorCount++;
    //                 continue;
    //             } else {
    //                 // Update record forecast sebelumnya
    //                 $prevPeriode = Forecast::getPreviousPeriode($nextPeriode);

    //                 $prevForecast = ForecastHistory::find()
    //                     ->where([
    //                         'barang_produksi_id' => $barang->barang_produksi_id,
    //                         'periode_forecast' => $prevPeriode
    //                     ])
    //                     ->one();

    //                 if ($prevForecast) {
    //                     $actualData = RiwayatPenjualan::find()
    //                         ->where([
    //                             'barang_produksi_id' => $barang->barang_produksi_id,
    //                             'bulan_periode' => $prevPeriode
    //                         ])
    //                         ->sum('qty_penjualan');

    //                     if ($actualData) {
    //                         $prevForecast->data_aktual = $actualData;
    //                         $prevForecast->save(false);
    //                     }
    //                 }
    //             }

    //             // UPDATE/CREATE FORECAST AKTIF
    //             $existingForecast = Forecast::find()
    //                 ->where([
    //                     'barang_produksi_id' => $barang->barang_produksi_id,
    //                     'periode_forecast' => $nextPeriode
    //                 ])
    //                 ->one();

    //             if ($existingForecast) {
    //                 $existingForecast->nilai_alpha = $bestResult['alpha'];
    //                 $existingForecast->mape_test = $bestResult['mape'];
    //                 $existingForecast->hasil_forecast = $nextForecast;
    //                 $existingForecast->updated_at = date('Y-m-d H:i:s');
                    
    //                 if (!$existingForecast->save()) {
    //                     $errors = implode(', ', $existingForecast->getErrorSummary(true));
    //                     $errorMessages[] = "Gagal update forecast aktif untuk '{$barang->nama}': {$errors}";
    //                     $errorCount++;
    //                 }
    //             } else {
    //                 $forecast = new Forecast();
    //                 $forecast->barang_produksi_id = $barang->barang_produksi_id;
    //                 $forecast->periode_forecast = $nextPeriode;
    //                 $forecast->nilai_alpha = $bestResult['alpha'];
    //                 $forecast->mape_test = $bestResult['mape'];
    //                 $forecast->hasil_forecast = $nextForecast;
    //                 $forecast->created_at = date('Y-m-d H:i:s');

    //                 if (!$forecast->save()) {
    //                     $errors = implode(', ', $forecast->getErrorSummary(true));
    //                     $errorMessages[] = "Gagal menyimpan forecast aktif untuk '{$barang->nama}': {$errors}";
    //                     $errorCount++;
    //                 } else {
    //                     $successCount++;
    //                 }
    //             }
    //         }

    //         // Set flash message
    //         $messages = [];
            
    //         if ($successCount > 0) {
    //             $messages[] = "✓ Berhasil membuat {$successCount} forecast baru.";
    //         }
            
    //         if ($skippedCount > 0) {
    //             $messages[] = "⚠ {$skippedCount} barang dilewati (sudah ada forecast atau data kurang).";
    //         }
            
    //         if ($errorCount > 0) {
    //             $messages[] = "✗ {$errorCount} forecast gagal dibuat.";
    //         }

    //         if (!empty($messages)) {
    //             Yii::$app->session->setFlash('success', implode('<br>', $messages));
    //         }
            
    //         if (!empty($errorMessages) && count($errorMessages) <= 10) {
    //             Yii::$app->session->setFlash('warning', implode('<br>', $errorMessages));
    //         }

    //         return $this->redirect(['index']);

    //     } catch (\Exception $e) {
    //         Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //         return $this->redirect(['index']);
    //     }
    // }

    public function actionCreate()
    {
        try {
            // Ambil daftar barang unik dari riwayat penjualan (produksi & custom)
            $barangList = RiwayatPenjualan::find()
                ->select([
                    new \yii\db\Expression("
                        COALESCE(barang_produksi_id, barang_custom_pelanggan_id) AS barang_id,
                        CASE 
                            WHEN barang_produksi_id IS NOT NULL THEN 'produksi'
                            ELSE 'custom'
                        END AS tipe_barang
                    ")
                ])
                ->groupBy(['barang_produksi_id', 'barang_custom_pelanggan_id'])
                ->asArray()
                ->all();

            if (empty($barangList)) {
                Yii::$app->session->setFlash('error', 'Tidak ada data penjualan yang bisa diproses.');
                return $this->redirect(['index']);
            }

            $successCount = 0;
            $errorCount = 0;
            $skippedCount = 0;
            $errorMessages = [];
            $today = date('Y-m-d');

            foreach ($barangList as $barang) {
                $tipe = $barang['tipe_barang'];
                $barangId = $barang['barang_id'];

                Yii::info(str_repeat("=", 60), __METHOD__);
                Yii::info("MEMPROSES BARANG {$tipe} ID: {$barangId}", __METHOD__);

                // Ambil data penjualan per bulan
                $riwayatData = RiwayatPenjualan::find()
                    ->select(['bulan_periode', 'SUM(qty_penjualan) AS total_qty'])
                    ->andFilterWhere([
                        $tipe == 'produksi' ? 'barang_produksi_id' : 'barang_custom_pelanggan_id' => $barangId
                    ])
                    ->groupBy('bulan_periode')
                    ->orderBy(['bulan_periode' => SORT_ASC])
                    ->asArray()
                    ->all();

                if (empty($riwayatData) || count($riwayatData) < 12) {
                    $skippedCount++;
                    $errorMessages[] = "Barang {$barangId} ({$tipe}) dilewati (data < 12 bulan).";
                    continue;
                }

                $salesData = [];
                foreach ($riwayatData as $r) {
                    $salesData[] = ['periode' => $r['bulan_periode'], 'qty' => $r['total_qty']];
                }

                $lastPeriode = end($salesData)['periode'];
                $nextPeriode = Forecast::getNextPeriode($lastPeriode);

                // Cek jika forecast sudah ada
                $existingHistory = ForecastHistory::find()
                    ->where([
                        $tipe == 'produksi' ? 'barang_produksi_id' : 'barang_custom_pelanggan_id' => $barangId,
                        'periode_forecast' => $nextPeriode
                    ])
                    ->one();

                if ($existingHistory) {
                    Yii::info("⚠ Forecast {$tipe} ID {$barangId} sudah ada untuk periode {$nextPeriode}.", __METHOD__);
                    $skippedCount++;
                    continue;
                }

                // Hitung alpha terbaik dan forecast
                $bestResult = $this->findBestAlphaWithDetail($salesData, "{$tipe}-{$barangId}");
                if ($bestResult === false) {
                    $errorCount++;
                    $errorMessages[] = "Gagal hitung forecast untuk {$tipe} ID {$barangId}";
                    continue;
                }

                $nextForecast = $this->calculateNextPeriodForecast($salesData, $bestResult['alpha']);

                // Simpan ForecastHistory
                $forecastHistory = new ForecastHistory();
                if ($tipe == 'produksi') {
                    $forecastHistory->barang_produksi_id = $barangId;
                } else {
                    $forecastHistory->barang_custom_pelanggan_id = $barangId;
                }
                $forecastHistory->periode_forecast = $nextPeriode;
                $forecastHistory->tanggal_dibuat = $today;
                $forecastHistory->nilai_alpha = $bestResult['alpha'];
                $forecastHistory->mape_test = $bestResult['mape'];
                $forecastHistory->hasil_forecast = $nextForecast;
                $forecastHistory->data_aktual = null;
                $forecastHistory->save(false);

                // Simpan/Update Forecast aktif
                $forecast = Forecast::find()
                    ->where([
                        $tipe == 'produksi' ? 'barang_produksi_id' : 'barang_custom_pelanggan_id' => $barangId,
                        'periode_forecast' => $nextPeriode
                    ])
                    ->one();

                if (!$forecast) {
                    $forecast = new Forecast();
                    if ($tipe == 'produksi') {
                        $forecast->barang_produksi_id = $barangId;
                    } else {
                        $forecast->barang_custom_pelanggan_id = $barangId;
                    }
                    $forecast->periode_forecast = $nextPeriode;
                    $forecast->created_at = date('Y-m-d H:i:s');
                }

                $forecast->nilai_alpha = $bestResult['alpha'];
                $forecast->mape_test = $bestResult['mape'];
                $forecast->hasil_forecast = $nextForecast;
                $forecast->updated_at = date('Y-m-d H:i:s');
                $forecast->save(false);

                $successCount++;
            }

            $messages = [];
            if ($successCount > 0) $messages[] = "✓ {$successCount} forecast berhasil dibuat.";
            if ($skippedCount > 0) $messages[] = "⚠ {$skippedCount} data dilewati.";
            if ($errorCount > 0) $messages[] = "✗ {$errorCount} forecast gagal.";

            Yii::$app->session->setFlash('success', implode('<br>', $messages));
            if (!empty($errorMessages)) Yii::$app->session->setFlash('warning', implode('<br>', $errorMessages));

            return $this->redirect(['index']);

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }


    /**
     * Mencari nilai alpha terbaik dengan MAPE terkecil
     * DENGAN DETAIL LOG SEPERTI TABEL EXPONENTIAL SMOOTHING
     */
    private function findBestAlphaWithDetail($salesData, $namaBarang)
    {
        if (count($salesData) < 3) {
            return false;
        }

        $bestAlpha = null;
        $bestMAPE = PHP_FLOAT_MAX;

        Yii::info("\n" . str_repeat("=", 80), __METHOD__);
        Yii::info("MENCARI ALPHA TERBAIK UNTUK: {$namaBarang}", __METHOD__);
        Yii::info(str_repeat("=", 80), __METHOD__);

        for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
            $alpha = round($alpha, 1); // Pastikan presisi

            Yii::info("\n" . str_repeat("-", 80), __METHOD__);
            Yii::info("TESTING ALPHA = {$alpha}", __METHOD__);
            Yii::info(str_repeat("-", 80), __METHOD__);

            $mape = $this->calculateMAPEWithDetail($salesData, $alpha);

            Yii::info("MAPE untuk α={$alpha}: " . round($mape, 2) . "%", __METHOD__);

            if ($mape < $bestMAPE) {
                $bestMAPE = $mape;
                $bestAlpha = $alpha;
            }
        }

        // Jika tidak ditemukan alpha valid
        if ($bestAlpha === null) {
            $bestAlpha = 0.1; // default aman
        }

        return [
            'alpha' => round($bestAlpha, 2),
            'mape' => round($bestMAPE, 2)
        ];
    }

    /**
     * Menghitung MAPE dengan menampilkan detail seperti tabel
     */
    private function calculateMAPEWithDetail($salesData, $alpha)
    {
        $n = count($salesData);
        
        if ($n < 2) {
            return PHP_FLOAT_MAX;
        }

        // Inisialisasi forecast pertama dengan data aktual pertama
        $forecast = $salesData[0]['qty'];
        $totalAPE = 0;
        $validCount = 0;

        // Header tabel
        Yii::info("\nTabel Perhitungan Exponential Smoothing (α = {$alpha}):", __METHOD__);
        Yii::info(sprintf("%-10s %-12s %-15s %-25s %-12s", 
            "Periode", "A (Aktual)", "F (Forecast)", "Rumus F", "Error %"), __METHOD__);
        Yii::info(str_repeat("-", 80), __METHOD__);

        // Periode pertama (inisialisasi)
        Yii::info(sprintf("%-10s %-12s %-15s %-25s %-12s", 
            "1", 
            $salesData[0]['qty'], 
            $forecast,
            "F1 = A1 (inisialisasi)",
            "-"), __METHOD__);

        // Mulai dari data kedua
        for ($i = 1; $i < $n; $i++) {
            $actual = $salesData[$i]['qty'];
            $prevActual = $salesData[$i-1]['qty'];
            
            // Hitung error hanya jika actual > 0
            $errorPct = "-";
            if ($actual > 0) {
                $error = abs($actual - $forecast) / $actual * 100;
                $errorPct = round($error, 2) . "%";
                $totalAPE += $error;
                $validCount++;
            }

            // Rumus untuk periode ini
            $rumus = sprintf("%.1f×%d + %.1f×%.0f", 
                $alpha, $prevActual, 
                (1-$alpha), $forecast);

            // Tampilkan baris tabel
            Yii::info(sprintf("%-10s %-12s %-15s %-25s %-12s", 
                ($i + 1), 
                $actual, 
                round($forecast),
                $rumus,
                $errorPct), __METHOD__);

            // Update forecast untuk periode berikutnya
            // F(t+1) = α * A(t) + (1 - α) * F(t)
            $forecast = $alpha * $actual + (1 - $alpha) * $forecast;
        }

        Yii::info(str_repeat("-", 80), __METHOD__);

        if ($validCount == 0) {
            return PHP_FLOAT_MAX;
        }

        $mape = $totalAPE / $validCount;
        Yii::info("Total Error: " . round($totalAPE, 2) . "%", __METHOD__);
        Yii::info("Jumlah periode valid: {$validCount}", __METHOD__);
        Yii::info("MAPE = " . round($totalAPE, 2) . " / {$validCount} = " . round($mape, 2) . "%\n", __METHOD__);

        return $mape;
    }

    /**
     * Menghitung forecast untuk periode berikutnya
     */
    private function calculateNextPeriodForecast($salesData, $alpha)
    {
        $n = count($salesData);
        
        if ($n < 1) {
            return 0;
        }

        // Inisialisasi forecast pertama dengan data aktual pertama
        $forecast = $salesData[0]['qty'];

        // Hitung forecast sampai data terakhir
        for ($i = 1; $i < $n; $i++) {
            $actual = $salesData[$i]['qty'];
            // F(t+1) = α * A(t) + (1 - α) * F(t)
            $forecast = $alpha * $actual + (1 - $alpha) * $forecast;
        }

        // Forecast untuk periode berikutnya
        $lastActual = $salesData[$n - 1]['qty'];
        $nextForecast = $alpha * $lastActual + (1 - $alpha) * $forecast;

        return round($nextForecast);
    }

    public function actionUpdate($forecast_id)
    {
        $model = $this->findModel($forecast_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Forecast berhasil diupdate.');
            return $this->redirect(['view', 'forecast_id' => $model->forecast_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($forecast_id)
    {
        $this->findModel($forecast_id)->delete();
        Yii::$app->session->setFlash('success', 'Forecast berhasil dihapus.');
        return $this->redirect(['index']);
    }

    protected function findModel($forecast_id)
    {
        if (($model = Forecast::findOne(['forecast_id' => $forecast_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}