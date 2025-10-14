<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EoqRop $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="eoq-rop-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'barang_id')->textInput() ?>

    <?= $form->field($model, 'total_bom')->textInput() ?>

    <?= $form->field($model, 'biaya_pesan_snapshot')->textInput() ?>

    <?= $form->field($model, 'biaya_simpan_snapshot')->textInput() ?>

    <?= $form->field($model, 'safety_stock_snapshot')->textInput() ?>

    <?= $form->field($model, 'lead_time_snapshot')->textInput() ?>

    <?= $form->field($model, 'demand_snapshot')->textInput() ?>

    <?= $form->field($model, 'total_biaya_persediaan')->textInput() ?>

    <?= $form->field($model, 'hasil_eoq')->textInput() ?>

    <?= $form->field($model, 'hasil_rop')->textInput() ?>

    <?= $form->field($model, 'periode')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
