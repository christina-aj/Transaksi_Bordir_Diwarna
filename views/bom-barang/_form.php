<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bom-barang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_produksi_id')->textInput() ?>

    <?= $form->field($model, 'total_bahan_baku')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
