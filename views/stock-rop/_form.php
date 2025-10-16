<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\StockRop $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-rop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_id')->textInput() ?>

    <?= $form->field($model, 'periode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stock_barang')->textInput() ?>

    <?= $form->field($model, 'safety_stock')->textInput() ?>

    <?= $form->field($model, 'jumlah_eoq')->textInput() ?>

    <?= $form->field($model, 'jumlah_rop')->textInput() ?>

    <?= $form->field($model, 'pesan_barang')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
