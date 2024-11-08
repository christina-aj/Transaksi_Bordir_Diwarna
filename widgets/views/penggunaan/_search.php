<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="penggunaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'penggunaan_id') ?>

    <?= $form->field($model, 'barang_id') ?>

    <?= $form->field($model, 'jumlah_digunakan') ?>

    <?= $form->field($model, 'tanggal_digunakan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
