<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitunganSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="data-perhitungan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'data_perhitungan_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'biaya_pesan') ?>

    <?= $form->field($model, 'biaya_simpan') ?>

    <?= $form->field($model, 'safety_stock') ?>

    <?php // echo $form->field($model, 'lead_time_rerata') ?>

    <?php // echo $form->field($model, 'periode_mulasi') ?>

    <?php // echo $form->field($model, 'periode_selesai') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
