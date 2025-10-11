<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitungan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-perhitungan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_id')->textInput() ?>

    <?= $form->field($model, 'biaya_pesan')->textInput() ?>

    <?= $form->field($model, 'biaya_simpan')->textInput() ?>

    <?= $form->field($model, 'safety_stock')->textInput() ?>

    <?= $form->field($model, 'lead_time_rerata')->textInput() ?>

    <?= $form->field($model, 'periode_mulasi')->textInput() ?>

    <?= $form->field($model, 'periode_selesai')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
