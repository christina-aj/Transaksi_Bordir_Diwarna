<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistory $model */

$this->title = 'Update Forecast History: ' . $model->forecast_history_id;
$this->params['breadcrumbs'][] = ['label' => 'Forecast Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->forecast_history_id, 'url' => ['view', 'forecast_history_id' => $model->forecast_history_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forecast-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
