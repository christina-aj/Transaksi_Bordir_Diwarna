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

    public function actionCreate()
    {
        try {
            // Ambil semua barang produksi
            $barangList = BarangProduksi::find()->all();
            
            if (empty($barangList)) {
                Yii::$app->session->setFlash('error', 'Tidak ada data barang produksi.');
                return $this->redirect(['index']);
            }

            $successCount = 0;
            $errorCount = 0;
            $skippedCount = 0;
            $errorMessages = [];

            foreach ($barangList as $barang) {
                // Ambil data riwayat penjualan untuk barang ini
                // Group by bulan_periode dan sum qty_penjualan
                $riwayatData = RiwayatPenjualan::find()
                    ->select([
                        'bulan_periode',
                        'SUM(qty_penjualan) as total_qty'
                    ])
                    ->where(['barang_produksi_id' => $barang->barang_produksi_id])
                    ->groupBy('bulan_periode')
                    ->orderBy(['bulan_periode' => SORT_ASC])
                    ->asArray()
                    ->all();

                // Skip jika tidak ada riwayat penjualan
                if (empty($riwayatData)) {
                    $skippedCount++;
                    continue;
                }

                // Cek apakah data sudah 12 bulan (1 tahun)
                if (count($riwayatData) < 12) {
                    $errorMessages[] = "Barang '{$barang->nama}' belum memiliki data 12 bulan (hanya " . count($riwayatData) . " bulan). Dilewati.";
                    $skippedCount++;
                    continue;
                }

                // Ambil data penjualan untuk perhitungan
                $salesData = [];
                foreach ($riwayatData as $riwayat) {
                    $salesData[] = [
                        'periode' => $riwayat['bulan_periode'],
                        'qty' => $riwayat['total_qty']
                    ];
                }

                // Hitung alpha terbaik dan MAPE
                $bestResult = $this->findBestAlpha($salesData);

                if ($bestResult === false) {
                    $errorMessages[] = "Gagal menghitung forecast untuk barang '{$barang->nama}'";
                    $errorCount++;
                    continue;
                }

                // Hitung forecast untuk periode berikutnya
                $nextForecast = $this->calculateNextPeriodForecast($salesData, $bestResult['alpha']);

                // Generate periode berikutnya (1 bulan setelah data terakhir)
                $lastPeriode = $salesData[count($salesData) - 1]['periode'];
                $nextPeriode = Forecast::getNextPeriode($lastPeriode);

                // Cek apakah forecast untuk barang dan periode ini sudah ada
                $existingForecast = Forecast::find()
                    ->where([
                        'barang_produksi_id' => $barang->barang_produksi_id,
                        'periode_forecast' => $nextPeriode
                    ])
                    ->one();

                if ($existingForecast) {
                    // Update forecast yang sudah ada
                    $existingForecast->nilai_alpha = $bestResult['alpha'];
                    $existingForecast->mape_test = $bestResult['mape'];
                    $existingForecast->hasil_forecast = $nextForecast;
                    
                    if (!$existingForecast->save()) {
                        $errors = implode(', ', $existingForecast->getErrorSummary(true));
                        $errorMessages[] = "Gagal update forecast untuk barang '{$barang->nama}': {$errors}";
                        $errorCount++;
                    } else {
                        $successCount++;
                    }
                } else {
                    // Buat forecast baru
                    $forecast = new Forecast();
                    $forecast->barang_produksi_id = $barang->barang_produksi_id;
                    $forecast->periode_forecast = $nextPeriode;
                    $forecast->nilai_alpha = $bestResult['alpha'];
                    $forecast->mape_test = $bestResult['mape'];
                    $forecast->hasil_forecast = $nextForecast;

                    if (!$forecast->save()) {
                        $errors = implode(', ', $forecast->getErrorSummary(true));
                        $errorMessages[] = "Gagal menyimpan forecast untuk barang '{$barang->nama}': {$errors}";
                        $errorCount++;
                    } else {
                        $successCount++;
                    }
                }
            }

            // Set flash message
            $messages = [];
            
            if ($successCount > 0) {
                $messages[] = "✓ Berhasil membuat/update {$successCount} forecast.";
            }
            
            if ($skippedCount > 0) {
                $messages[] = "⚠ {$skippedCount} barang dilewati (tidak ada riwayat atau data kurang dari 12 bulan).";
            }
            
            if ($errorCount > 0) {
                $messages[] = "✗ {$errorCount} forecast gagal dibuat.";
            }

            if (!empty($messages)) {
                Yii::$app->session->setFlash('success', implode('<br>', $messages));
            }
            
            if (!empty($errorMessages) && count($errorMessages) <= 10) {
                Yii::$app->session->setFlash('warning', implode('<br>', $errorMessages));
            }

            return $this->redirect(['index']);

        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Mencari nilai alpha terbaik dengan MAPE terkecil
     * Menggunakan metode Single Exponential Smoothing
     */
    private function findBestAlpha($salesData)
    {
        if (count($salesData) < 3) {
            return false;
        }

        $bestAlpha = 0;
        $bestMAPE = PHP_FLOAT_MAX;

        // Coba alpha dari 0.1 sampai 0.9 dengan step 0.1
        for ($alpha = 0.1; $alpha <= 0.9; $alpha += 0.1) {
            $mape = $this->calculateMAPE($salesData, $alpha);
            
            if ($mape < $bestMAPE) {
                $bestMAPE = $mape;
                $bestAlpha = $alpha;
            }
        }

        // Refinement: coba di sekitar alpha terbaik dengan step lebih kecil
        $startAlpha = max(0.01, $bestAlpha - 0.09);
        $endAlpha = min(0.99, $bestAlpha + 0.09);
        
        for ($alpha = $startAlpha; $alpha <= $endAlpha; $alpha += 0.01) {
            $mape = $this->calculateMAPE($salesData, $alpha);
            
            if ($mape < $bestMAPE) {
                $bestMAPE = $mape;
                $bestAlpha = $alpha;
            }
        }

        return [
            'alpha' => round($bestAlpha, 2),
            'mape' => round($bestMAPE, 2)
        ];
    }

    /**
     * Menghitung MAPE (Mean Absolute Percentage Error)
     */
    private function calculateMAPE($salesData, $alpha)
    {
        $n = count($salesData);
        
        if ($n < 2) {
            return PHP_FLOAT_MAX;
        }

        // Inisialisasi forecast pertama dengan data aktual pertama
        $forecast = $salesData[0]['qty'];
        $totalAPE = 0;
        $validCount = 0;

        // Mulai dari data kedua
        for ($i = 1; $i < $n; $i++) {
            $actual = $salesData[$i]['qty'];
            
            // Hitung error hanya jika actual > 0
            if ($actual > 0) {
                $error = abs($actual - $forecast) / $actual * 100;
                $totalAPE += $error;
                $validCount++;
            }

            // Update forecast untuk periode berikutnya
            // F(t+1) = α * A(t) + (1 - α) * F(t)
            $forecast = $alpha * $actual + (1 - $alpha) * $forecast;
        }

        if ($validCount == 0) {
            return PHP_FLOAT_MAX;
        }

        return $totalAPE / $validCount;
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