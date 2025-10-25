<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="master-pelanggan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode_pelanggan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_pelanggan')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
