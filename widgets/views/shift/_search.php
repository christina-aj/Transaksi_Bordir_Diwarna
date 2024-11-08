<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Shiftsearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="shift-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'shift_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'tanggal') ?>

    <?= $form->field($model, 'shift') ?>

    <?= $form->field($model, 'waktu_kerja') ?>

    <?php echo $form->field($model, 'nama_operator') ?>

    <?php  echo $form->field($model, 'mulai_istirahat') ?>

    <?php echo $form->field($model, 'selesai_istirahat') ?>

    <?php echo $form->field($model, 'kendala') ?>

    <?php echo $form->field($model, 'ganti_benang') ?>

    <?php  echo $form->field($model, 'ganti_kain') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
