<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\LaporanProduksisearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="laporan-produksi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'laporan_id') ?>

    <?= $form->field($model, 'mesin_id') ?>

    <?= $form->field($model, 'shift_id') ?>

    <?= $form->field($model, 'tanggal_kerja') ?>

    <?= $form->field($model, 'nama_kerjaan') ?>

    <?php  echo $form->field($model, 'vs') ?>

    <?php  echo $form->field($model, 'stitch') ?>

    <?php  echo $form->field($model, 'kuantitas') ?>

    <?php  echo $form->field($model, 'bs') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
