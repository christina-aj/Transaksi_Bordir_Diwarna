<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="barangproduksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?>

    <?php
    $dataMesin = ArrayHelper::map(\app\models\Jenis::find()->asArray()->all(), 'nama_jenis', 'nama_jenis');
    echo $form->field($model, 'nama_jenis')
        ->dropDownList(
            $dataMesin,
            ['prompt'=>'Jenis']
        );
    ?>

    <?= $form->field($model, 'deskripsi')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
