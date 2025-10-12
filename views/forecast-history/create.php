<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistory $model */

$this->title = 'Create Forecast History';
$this->params['breadcrumbs'][] = ['label' => 'Forecast Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forecast-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
