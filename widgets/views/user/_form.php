<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'id_role')->textInput() ?> -->

    <?php
    $dataPost = ArrayHelper::map(\app\models\Role::find()->asArray()->all(), 'id_role', 'nama');
    echo $form->field($model, 'id_role')
        ->dropDownList(
            $dataPost,
            ['id_role' => 'nama']
        );
    ?>

    <?= $form->field($model, 'nama_pengguna')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kata_sandi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>


    <!-- <?= $form->field($model, 'dibuat_pada')->hiddenInput() ?> -->

    <!-- <?= $form->field($model, 'diperbarui_pada')->hiddenInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>