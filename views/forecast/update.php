<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Forecast $model */

$this->title = 'Update Forecast: ' . $model->forecast_id;
$this->params['breadcrumbs'][] = ['label' => 'Forecasts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->forecast_id, 'url' => ['view', 'forecast_id' => $model->forecast_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forecast-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
