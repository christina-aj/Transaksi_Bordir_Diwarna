<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\UserSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'id_role') ?>

    <?= $form->field($model, 'nama_pengguna') ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'kata_sandi') ?>

    <?php // echo $form->field($model, 'dibuat_pada') ?>

    <?php // echo $form->field($model, 'diperbarui_pada') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
