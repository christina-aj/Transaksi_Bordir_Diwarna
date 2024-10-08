<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pembelian_id')->textInput() ?>

    <?= $form->field($model, 'pesandetail_id')->textInput() ?>

    <?= $form->field($model, 'cek_barang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_biaya')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_correct')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
