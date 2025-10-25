<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPelangganSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="permintaan-pelanggan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'permintaan_id') ?>

    <?= $form->field($model, 'pelanggan_id') ?>

    <?= $form->field($model, 'tipe_pelanggan') ?>

    <?= $form->field($model, 'total_item_permintaan') ?>

    <?php // echo $form->field($model, 'tanggal_permintaan') ?>

    <?php // echo $form->field($model, 'status_permintaan') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
