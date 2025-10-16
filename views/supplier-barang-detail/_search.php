<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="supplier-barang-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'supplier_barang_detail_id') ?>

    <?= $form->field($model, 'supplier_barang_id') ?>

    <?= $form->field($model, 'supplier_id') ?>

    <?= $form->field($model, 'lead_time') ?>

    <?= $form->field($model, 'harga_per_kg') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
