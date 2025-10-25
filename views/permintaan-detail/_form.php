<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="permintaan-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'permintaan_id')->textInput() ?>

    <?= $form->field($model, 'barang_produksi_id')->textInput() ?>

    <?= $form->field($model, 'barang_custom_pelanggan_id')->textInput() ?>

    <?= $form->field($model, 'qty_permintaan')->textInput() ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
