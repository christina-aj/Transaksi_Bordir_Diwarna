<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistory $model */

$this->title = $model->forecast_history_id;
$this->params['breadcrumbs'][] = ['label' => 'Forecast Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forecast-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'forecast_history_id' => $model->forecast_history_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'forecast_history_id' => $model->forecast_history_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'forecast_history_id',
            'barang_produksi_id',
            'periode_forecast',
            'nilai_alpha',
            'mape_test',
            'hasil_forecast',
            'data_aktual',
            'selisih',
            'tanggal_dibuat',
        ],
    ]) ?>

</div>
