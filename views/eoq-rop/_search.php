<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\EoqRopSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="eoq-rop-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'EOQ_ROP_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'total_bom') ?>

    <?= $form->field($model, 'biaya_pesan_snapshot') ?>

    <?= $form->field($model, 'biaya_simpan_snapshot') ?>

    <?php // echo $form->field($model, 'safety_stock_snapshot') ?>

    <?php // echo $form->field($model, 'lead_time_snapshot') ?>

    <?php // echo $form->field($model, 'demand_snapshot') ?>

    <?php // echo $form->field($model, 'total_biaya_persediaan') ?>

    <?php // echo $form->field($model, 'hasil_eoq') ?>

    <?php // echo $form->field($model, 'hasil_rop') ?>

    <?php // echo $form->field($model, 'periode') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
