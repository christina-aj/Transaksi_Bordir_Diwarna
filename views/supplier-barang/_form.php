<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="supplier-barang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'supplier_barang_id')->textInput() ?>

    <?= $form->field($model, 'barang_id')->textInput() ?>

    <?= $form->field($model, 'supplier_id')->textInput() ?>

    <?= $form->field($model, 'lead_time')->textInput() ?>

    <?= $form->field($model, 'harga_per_kg')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
