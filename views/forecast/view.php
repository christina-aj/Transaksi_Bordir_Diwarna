<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Forecast $model */

$this->title = $model->forecast_id;
$this->params['breadcrumbs'][] = ['label' => 'Forecasts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="forecast-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'forecast_id' => $model->forecast_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'forecast_id' => $model->forecast_id], [
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
            'forecast_id',
            'riwayat_penjualan_id',
            'periode_forecast',
            'nilai_alpha',
            'mape_test',
            'hasil_forecast',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
