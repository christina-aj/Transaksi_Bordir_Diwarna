<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LaporanAgregatsearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="laporan-agregat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'laporan_id') ?>

    <?= $form->field($model, 'mesin_id') ?>

    <?= $form->field($model, 'shift_id') ?>

    <?= $form->field($model, 'tanggal_kerja') ?>

    <?= $form->field($model, 'nama_kerjaan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
