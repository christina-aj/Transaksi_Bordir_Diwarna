<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetailSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-detail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'belidetail_id') ?>

    <?= $form->field($model, 'pembelian_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'harga_barang') ?>

    <?= $form->field($model, 'quantity_barang') ?>

    <?php // echo $form->field($model, 'total_biaya') ?>

    <?php // echo $form->field($model, 'catatan') ?>

    <?php // echo $form->field($model, 'langsung_pakai') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
