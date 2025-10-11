<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Forecast $model */

$this->title = 'Create Forecast';
$this->params['breadcrumbs'][] = ['label' => 'Forecasts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forecast-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
