<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Forecast $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="forecast-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'riwayat_penjualan_id')->textInput() ?>

    <?= $form->field($model, 'periode_forecast')->textInput() ?>

    <?= $form->field($model, 'nilai_alpha')->textInput() ?>

    <?= $form->field($model, 'mape_test')->textInput() ?>

    <?= $form->field($model, 'hasil_forecast')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
