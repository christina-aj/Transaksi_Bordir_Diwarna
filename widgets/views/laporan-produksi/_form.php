<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Shift;


/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pc-content">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $dataMesin = ArrayHelper::map(\app\models\Mesin::find()->asArray()->all(), 'nama', 'nama');
    echo $form->field($model, 'nama_mesin')
        ->dropDownList(
            $dataMesin,
            ['prompt'=>'Select Mesin']
        );
    ?>

    <?php
    $dataShift = ArrayHelper::map(Shift::find()->asArray()->all(), 'shift_id', function($model) {
        $shiftTime = ($model['shift'] == 1) ? 'Pagi' : 'Sore';
        return $model['shift_id'] . ' - ' . $model['nama_operator'] . ' (' . $shiftTime . ')';
    });
    
    echo $form->field($model, 'shift_id')
        ->dropDownList(
            $dataShift,
            ['prompt'=>'Select Shift']
        );
    ?>

    <?= $form->field($model, 'tanggal_kerja')->widget(\kartik\date\DatePicker::classname(), [
        'options' => [
            'placeholder' => 'Pilih tanggal ...',
            'onchange' => '
                var date = $(this).val();
                $.ajax({
                    url: "' . \yii\helpers\Url::to(['laporan-produksi/get-shifts']) . '",
                    type: "POST",
                    data: {date: date},
                    success: function(data) {
                        $("#laporanproduksi-shift_id").html(data);
                    }
                });
            ',
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
            'todayHighlight' => true,
        ],
    ]); ?>

    <?= $form->field($model, 'nama_kerjaan')->textInput(['maxlength' => true]) ?>

    <?php
    $dataBarang = ArrayHelper::map(\app\models\Barangproduksi::find()->asArray()->all(), 'nama', 'nama');
    echo $form->field($model, 'nama_barang')
        ->dropDownList(
            $dataBarang,
            ['prompt'=>'Pilih Barang']
        );
    ?>

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


