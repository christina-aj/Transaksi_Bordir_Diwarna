<?php

use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Shift;
use app\models\Mesin;

/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $mesinlist */  

$mesinList = ArrayHelper::map(
    Mesin::find()->all(),
    'mesin_id',
    'nama'
);

$dataBarang = ArrayHelper::map(
    \app\models\Barangproduksi::find()
        ->select(['barang_produksi_id', 'nama'])
        ->asArray()
        ->all(), 
    'barang_produksi_id', 
    'nama'
);

$shiftId = Yii::$app->session->get('shift_id');
$tanggalKerja = Yii::$app->session->get('tanggal_kerja'); 

if ($shiftId !== null) {
    $model->shift_id = $shiftId;
}

if ($tanggalKerja !== null) {
    $model->tanggal_kerja = $tanggalKerja; 
}
?>

<div class="pc-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mesin_id')->dropDownList(
        $mesinList,
        [
            'prompt' => 'Pilih Mesin',
            'id' => 'mesin-dropdown',
        ]
    ) ?>


    <?php
    $dataShift = ArrayHelper::map(Shift::find()->asArray()->all(), 'shift_id', function($model) {
        $shiftTime = ($model['shift'] == 1) ? 'Pagi' : 'Sore';
        return $model['shift_id'] . ' - ' . $model['nama_operator'] . ' (' . $shiftTime . ')';
    });
    
    echo $form->field($model, 'shift_id')
        ->dropDownList(
            $dataShift,
            ['prompt'=>'Select Shift','readonly' => true]
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
        'readonly' => true,
        ]); ?>

    <?= $form->field($model, 'nama_kerjaan')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'nama_barang')->widget(Select2::classname(), [
        'data' => $dataBarang,
        'options' => [
            'placeholder' => 'Pilih Barang',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '100%'
        ],
    ]);
    ?>

    <div id="bordir-fields" style="display: none;">
        <?= $form->field($model, 'vs')->textInput() ?>
        <?= $form->field($model, 'stitch')->textInput() ?>
    </div>

    <div id="kaoskaki-fields" style="display: none;">
        <?= $form->field($model, 'berat')->textInput() ?>
    </div>

    <?= $form->field($model, 'kuantitas')->textInput() ?>

    <?= $form->field($model, 'bs')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['laporan-produksi/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
$(document).ready(function() {
    $('#mesin-dropdown').change(function() {
        var mesinId = $(this).val();
        
        
        $('#bordir-fields').hide();
        $('#kaoskaki-fields').hide();
        
        if(mesinId) {
            
            $.get('/mesin/get-kategori', {id: mesinId}, function(data) {
                if(data.kategori === '1') {  
                    $('#bordir-fields').show();
                } else if(data.kategori === '2') { 
                    $('#kaoskaki-fields').show();
                }
            });
        }
    });
});
JS;
$this->registerJs($script);
?>
