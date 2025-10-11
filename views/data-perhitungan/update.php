<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitungan $model */

$this->title = 'Update Data Perhitungan: ' . $model->data_perhitungan_id;
$this->params['breadcrumbs'][] = ['label' => 'Data Perhitungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->data_perhitungan_id, 'url' => ['view', 'data_perhitungan_id' => $model->data_perhitungan_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="data-perhitungan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
