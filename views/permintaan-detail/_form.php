<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="permintaan-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'permintaan_penjualan_id')->textInput() ?>

    <?= $form->field($model, 'barang_produksi_id')->textInput() ?>

    <?= $form->field($model, 'qty_permintaan')->textInput() ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
