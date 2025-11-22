<?php

use app\models\ForecastHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Forecast History';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
        <div class="card card-table">
            <div class="card-header">
                <h1><?= Html::encode($this->title) ?></h1>
                <p>
                    <?= \yii\helpers\Html::a('Update Data Aktual', ['forecast/update-actual'], [
                        'class' => 'btn btn-warning',
                        'data-confirm' => 'Yakin mau update data aktual bulan lalu?',
                    ]) ?>
                </p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // 'forecast_history_id',
                            // [
                            //     'attribute' => 'barang_produksi_id',
                            //     'label' => 'Nama Barang',
                            //     'value' => function($model) {
                            //         return $model->barangProduksi->nama ?? '';
                            //     }
                            // ],
                            // 'barang_produksi_id',
                            // 'periode_forecast',
                            [
                                'attribute' => 'periode_forecast',
                                'label' => 'Periode',
                                'value' => function($model) {
                                    return $model->periodeForecastFormatted;
                                }
                            ],
                            'nilai_alpha',
                            'nilai_beta',
                            'nilai_gamma',
                            'mape_test',
                            'hasil_forecast',
                            'data_aktual',
                            'selisih',
                            // 'tanggal_dibuat',
                            // [
                            //     'class' => ActionColumn::className(),
                            //     'urlCreator' => function ($action, ForecastHistory $model, $key, $index, $column) {
                            //         return Url::toRoute([$action, 'forecast_history_id' => $model->forecast_history_id]);
                            //     }
                            // ],
                        ],
                    ]); ?>
                    <p>
                        <?= Html::a('Back', ['forecast/index'], ['class' => 'btn btn-secondary']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
