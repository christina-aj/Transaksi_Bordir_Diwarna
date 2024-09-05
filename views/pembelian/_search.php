<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PembelianSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'pembelian_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'supplier_id') ?>

    <?= $form->field($model, 'total_biaya') ?>

    <?php // echo $form->field($model, 'langsung_pakai') ?>

    <?php // echo $form->field($model, 'kode_struk') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
