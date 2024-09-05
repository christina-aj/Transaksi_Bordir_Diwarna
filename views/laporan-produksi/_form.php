<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Shift;


/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="laporan-produksi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    // Fetch the list of machines
    $dataMesin = ArrayHelper::map(\app\models\Mesin::find()->asArray()->all(), 'mesin_id', 'mesin_id');
    echo $form->field($model, 'mesin_id')
        ->dropDownList(
            $dataMesin,
            ['prompt'=>'Select Mesin']
        );
    ?>

    <?php
    $dataShift = ArrayHelper::map(Shift::find()->asArray()->all(), 'shift_id', function($model) {
        return $model['shift_id'] . ' - ' . $model['nama_operator'];
    });
    echo $form->field($model, 'shift_id')
        ->dropDownList(
            $dataShift,
            ['prompt'=>'Select Shift']
        );
    ?>

    <?= $form->field($model, 'tanggal_kerja')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'nama_kerjaan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vs')->textInput() ?>

    <?= $form->field($model, 'stitch')->textInput() ?>

    <?= $form->field($model, 'kuantitas')->textInput() ?>

    <?= $form->field($model, 'bs')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['laporan-produksi/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


