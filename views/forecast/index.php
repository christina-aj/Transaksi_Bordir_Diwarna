<?php

use app\models\Forecast;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ForecastSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Forecast Barang 4 Bulan';
$this->params['breadcrumbs'][] = $this->title;

// Prepare data untuk chart
$chartLabels = [];
$chartHasil = [];
$chartMape = [];
$chartColors = [];

$forecasts = $dataProvider->models;
foreach ($forecasts as $forecast) {
    $chartLabels[] = $forecast->periodeForecastFormatted;
    $chartHasil[] = (float)$forecast->hasil_forecast;
    $mape = (float)$forecast->mape_test;
    $chartMape[] = $mape;
    
    // Warna berdasarkan MAPE
    if ($mape < 10) {
        $chartColors[] = 'rgba(40, 167, 69, 0.7)';
    } elseif ($mape < 20) {
        $chartColors[] = 'rgba(255, 193, 7, 0.7)';
    } else {
        $chartColors[] = 'rgba(220, 53, 69, 0.7)';
    }
}

$hasData = count($chartLabels) > 0;

// Convert to JSON
$labelsJson = json_encode($chartLabels);
$hasilJson = json_encode($chartHasil);
$mapeJson = json_encode($chartMape);
$colorsJson = json_encode($chartColors);

// JavaScript untuk chart
if ($hasData) {
    $js = <<<JS
    console.log('Initializing charts...');
    
    // Wait for Chart.js to load
    function initCharts() {
        if (typeof Chart === 'undefined') {
            console.log('Chart.js not loaded yet, retrying...');
            setTimeout(initCharts, 100);
            return;
        }
        
        console.log('Chart.js loaded, creating charts...');
        
        const labels = $labelsJson;
        const hasilData = $hasilJson;
        const mapeData = $mapeJson;
        const colors = $colorsJson;
        
        console.log('Data:', {labels, hasilData, mapeData, colors});
        
        // Chart Forecast
        const ctxForecast = document.getElementById('forecastChart');
        if (ctxForecast) {
            new Chart(ctxForecast, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Hasil Forecast (unit)',
                        data: hasilData,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: 'rgb(75, 192, 192)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Forecast 4 Bulan Ke Depan',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + 
                                           context.parsed.y.toLocaleString('id-ID') + ' unit';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('id-ID');
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            console.log('Forecast chart created');
        }
        
        // Chart MAPE
        const ctxMape = document.getElementById('mapeChart');
        if (ctxMape) {
            new Chart(ctxMape, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'MAPE (%)',
                        data: mapeData,
                        backgroundColor: colors,
                        borderColor: colors.map(c => c.replace('0.7', '1')),
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        },
                        title: {
                            display: true,
                            text: 'Akurasi Model (MAPE)',
                            font: {
                                size: 16,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed.y.toFixed(2);
                                    let status = '';
                                    if (context.parsed.y < 10) status = ' (Sangat Baik)';
                                    else if (context.parsed.y < 20) status = ' (Baik)';
                                    else status = ' (Cukup)';
                                    return 'MAPE: ' + value + '%' + status;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 30,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            console.log('MAPE chart created');
        }
    }
    
    // Initialize on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
JS;
    $this->registerJs($js, \yii\web\View::POS_END);
}

// CSS
$css = <<<CSS
.chart-container {
    position: relative;
    height: 350px;
    margin-bottom: 1rem;
    padding: 1rem;
}
.info-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.info-box i {
    font-size: 1.2rem;
    margin-right: 0.5rem;
}
.btn-group-custom {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    align-items: center;
}
.card-chart {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    border: 1px solid #e0e0e0;
}
.chart-section {
    margin-top: 2rem;
    margin-bottom: 2rem;
}
CSS;
$this->registerCss($css);
?>

<div class="pc-content">

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('warning') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="card-body">
            <!-- Info Box -->
            <div>
                <p class="mb-3">
                    <strong>Metode:</strong> Triple Exponential Smoothing (Holt-Winters) | 
                    <strong>Target:</strong> Total Penjualan Agregat (bukan per barang) | 
                    <strong>Periode:</strong> 4 Bulan Ke Depan | 
                    <strong>Frekuensi:</strong> 3x per tahun
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="btn-group-custom mb-4">
                <?php if ($canGenerate): ?>
                    <?= Html::a('<i class="fas fa-magic"></i> Generate Forecast', ['create'], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => 'Generate forecast untuk 4 bulan ke depan berdasarkan data historis total penjualan dari riwayat penjualan?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php else: ?>
                    <button class="btn btn-success" disabled>
                        <i class="fas fa-ban"></i> Belum Bisa Generate
                    </button>
                    <small class="text-muted">
                        <i class="fas fa-clock"></i> Tunggu 4 bulan sejak forecast terakhir
                    </small>
                <?php endif; ?>

                <?= Html::a('<i class="fas fa-history"></i> Lihat Riwayat Forecast', ['forecast-history/index'], [
                    'class' => 'btn btn-primary',
                ]) ?>
            </div>

            <?php if ($hasData): ?>
                <!-- Chart Section -->
                <div class="chart-section">
                    <div class="row">
                        <div class="col-lg-8 col-md-12">
                            <div class="card-chart">
                                <div class="chart-container">
                                    <canvas id="forecastChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12">
                            <div class="card-chart">
                                <div class="chart-container">
                                    <canvas id="mapeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada data forecast. Silakan generate forecast terlebih dahulu.
                </div>
            <?php endif; ?>

            <!-- Data Table -->
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'periode_forecast',
                            'label' => 'Periode',
                            'value' => function($model) {
                                return $model->periodeForecastFormatted;
                            }
                        ],
                        [
                            'attribute' => 'nilai_alpha',
                            'label' => 'Alpha (Level)',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'nilai_beta',
                            'label' => 'Beta (Trend)',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'nilai_gamma',
                            'label' => 'Gamma (Seasonal)',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'mape_test',
                            'label' => 'MAPE (%)',
                            'value' => function($model) {
                                $mape = $model->mape_test;
                                $badge = '';
                                if ($mape < 10) {
                                    $badge = '<span class="badge bg-success">Sangat Baik</span>';
                                } elseif ($mape < 20) {
                                    $badge = '<span class="badge bg-warning text-dark">Baik</span>';
                                } else {
                                    $badge = '<span class="badge bg-danger">Cukup</span>';
                                }
                                return number_format($mape, 2, ',', '.') . '% ' . $badge;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'hasil_forecast',
                            'label' => 'Hasil Forecast (unit)',
                            'value' => function($model) {
                                return number_format($model->hasil_forecast, 0, ',', '.');  
                            },
                            'contentOptions' => ['class' => 'text-end fw-bold'],
                        ],
                        // [
                        //     'class' => ActionColumn::className(),
                        //     'template' => '{delete}',
                        //     'urlCreator' => function ($action, Forecast $model, $key, $index, $column) {
                        //         return Url::toRoute([$action, 'forecast_id' => $model->forecast_id]);
                        //     },
                        //     'buttons' => [
                        //         'delete' => function ($url, $model, $key) {
                        //             return Html::a('<i class="fas fa-trash"></i>', $url, [
                        //                 'class' => 'btn btn-sm btn-danger',
                        //                 'data' => [
                        //                     'confirm' => 'Apakah Anda yakin ingin menghapus forecast ini?',
                        //                     'method' => 'post',
                        //                 ],
                        //             ]);
                        //         },
                        //     ],
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php
// Load Chart.js from CDN
$this->registerJsFile(
    'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js',
    ['position' => \yii\web\View::POS_HEAD]
);
?>