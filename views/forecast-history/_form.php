<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\ForecastHistory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="forecast-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_produksi_id')->textInput() ?>

    <?= $form->field($model, 'periode_forecast')->textInput() ?>

    <?= $form->field($model, 'nilai_alpha')->textInput() ?>

    <?= $form->field($model, 'mape_test')->textInput() ?>

    <?= $form->field($model, 'hasil_forecast')->textInput() ?>

    <?= $form->field($model, 'data_aktual')->textInput() ?>

    <?= $form->field($model, 'selisih')->textInput() ?>

    <?= $form->field($model, 'tanggal_dibuat')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
