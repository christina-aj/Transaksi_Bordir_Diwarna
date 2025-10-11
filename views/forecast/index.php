<?php

use app\models\Forecast;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ForecastSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Forecast Barang Per-Bulan';
$this->params['breadcrumbs'][] = $this->title;
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

    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Generate Forecast', ['create'], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Apakah Anda yakin ingin generate forecast untuk semua barang yang memiliki data 12 bulan?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'forecast_id',
                        [
                            'attribute' => 'barang_produksi_id',
                            'label' => 'Nama Barang',
                            'value' => function($model) {
                                return $model->namaBarang;
                            }
                        ],
                        [
                            'attribute' => 'periode_forecast',
                            'label' => 'Periode',
                            'value' => function($model) {
                                return $model->periodeForecastFormatted;
                            }
                        ],
                        [
                            'attribute' => 'nilai_alpha',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'mape_test',
                            'label' => 'MAPE (%)',
                            'format' => ['decimal', 2],
                        ],
                        [
                            'attribute' => 'hasil_forecast',
                            'label' => 'Hasil Forecast (unit)',
                            // 'format' => ['decimal', 2],
                                'value' => function($model) {
                                return number_format($model->hasil_forecast, 0, ',', '.');  
                                // Format: 892 atau 1.234 (dengan titik ribuan)
                            }
                        ],
                        // [
                        //     'attribute' => 'created_at',
                        //     'format' => ['datetime', 'php:d-m-Y H:i'],
                        // ],
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{delete}', // Hanya tombol delete
                            'urlCreator' => function ($action, Forecast $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'forecast_id' => $model->forecast_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>