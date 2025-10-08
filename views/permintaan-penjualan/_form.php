<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPenjualan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="permintaan-penjualan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'total_item_permintaan')->textInput() ?>

    <?= $form->field($model, 'tanggal_permintaan')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
