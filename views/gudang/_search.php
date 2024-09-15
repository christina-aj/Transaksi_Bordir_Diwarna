<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\GudangSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gudang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_gudang') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'quantity_awal') ?>

    <?php // echo $form->field($model, 'quantity_masuk') ?>

    <?php // echo $form->field($model, 'quantity_keluar') ?>

    <?php // echo $form->field($model, 'quantity_akhir') ?>

    <?php // echo $form->field($model, 'catatan') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'update_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
