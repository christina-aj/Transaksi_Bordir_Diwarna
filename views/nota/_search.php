<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Notasearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pc-content">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'nota_id') ?>

    <?= $form->field($model, 'nama_konsumen') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'barang') ?>

    <?= $form->field($model, 'harga') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'total_qty') ?>

    <?php // echo $form->field($model, 'total_harga') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
