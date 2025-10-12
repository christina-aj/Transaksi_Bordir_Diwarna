<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistorySearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="forecast-history-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'forecast_history_id') ?>

    <?= $form->field($model, 'barang_produksi_id') ?>

    <?= $form->field($model, 'periode_forecast') ?>

    <?= $form->field($model, 'nilai_alpha') ?>

    <?= $form->field($model, 'mape_test') ?>

    <?php // echo $form->field($model, 'hasil_forecast') ?>

    <?php // echo $form->field($model, 'data_aktual') ?>

    <?php // echo $form->field($model, 'selisih') ?>

    <?php // echo $form->field($model, 'tanggal_dibuat') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
