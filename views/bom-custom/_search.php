<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BomCustomSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bom-custom-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'BOM_custom_id') ?>

    <?= $form->field($model, 'barang_custom_pelanggan_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'qty_per_unit') ?>

    <?= $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
