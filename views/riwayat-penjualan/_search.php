<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-penjualan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'riwayat_penjualan_id') ?>

    <?= $form->field($model, 'barang_produksi_id') ?>

    <?= $form->field($model, 'qty_penjualan') ?>

    <?= $form->field($model, 'bulan_periode') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
