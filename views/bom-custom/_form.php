<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BomCustom $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bom-custom-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_custom_pelanggan_id')->textInput() ?>

    <?= $form->field($model, 'barang_id')->textInput() ?>

    <?= $form->field($model, 'qty_per_unit')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
