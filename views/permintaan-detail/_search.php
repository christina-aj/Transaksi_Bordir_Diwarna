<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="permintaan-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'permintaan_detail_id') ?>

    <?= $form->field($model, 'permintaan_penjualan_id') ?>

    <?= $form->field($model, 'barang_produksi_id') ?>

    <?= $form->field($model, 'qty_permintaan') ?>

    <?= $form->field($model, 'catatan') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
