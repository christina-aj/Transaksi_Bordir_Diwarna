<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StockRopSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-rop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'stock_rop_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'periode') ?>

    <?= $form->field($model, 'stock_barang') ?>

    <?= $form->field($model, 'safety_stock') ?>

    <?php // echo $form->field($model, 'jumlah_eoq') ?>

    <?php // echo $form->field($model, 'jumlah_rop') ?>

    <?php // echo $form->field($model, 'pesan_barang') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
