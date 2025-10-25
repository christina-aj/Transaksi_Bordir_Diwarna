<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BarangCustomPelangganSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="barang-custom-pelanggan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'barang_custom_pelanggan_id') ?>

    <?= $form->field($model, 'pelanggan_id') ?>

    <?= $form->field($model, 'kode_barang_custom') ?>

    <?= $form->field($model, 'nama_barang_custom') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
