<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Shift;


/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */
/** @var yii\widgets\ActiveForm $form */

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
        $mesinlist,
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
    $dataBarang = ArrayHelper::map(\app\models\Barangproduksi::find()->asArray()->all(), 'nama', 'nama');
    echo $form->field($model, 'nama_barang')
        ->dropDownList(
            $dataBarang,
            ['prompt'=>'Pilih Barang']
        );
    ?>

    <div id="vs-field" style="display: none;">
        <?= $form->field($model, 'vs')->textInput() ?>
    </div>

    <div id="stich-field" style="display: none;">
        <?= $form->field($model, 'stich')->textInput() ?>
    </div>

    <?= $form->field($model, 'kuantitas')->textInput() ?>

    <?= $form->field($model, 'bs')->textInput() ?>

    <div id="berat-field" style="display: none;">
        <?= $form->field($model, 'berat')->textInput() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['laporan-produksi/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<!-- JavaScript untuk Menyesuaikan Kolom -->
<?php
$script = <<<JS
document.getElementById('mesin-dropdown').addEventListener('change', function () {
    var mesinId = this.value;

    if (mesinId) {
        $.ajax({
            url: '/mesin/get-kategori',
            type: 'GET',
            data: { id: mesinId },
            success: function (response) {
                if (response.kategori === '1') {
                    $('#vs-field').show();
                    $('#stitch-field').show();
                    $('#berat-field').hide();
                } else if (response.kategori === '2') {
                    $('#vs-field').hide();
                    $('#stitch-field').hide();
                    $('#berat-field').show();
                } else {
                    $('#vs-field').hide();
                    $('#stitch-field').hide();
                    $('#berat-field').hide();
                }
            },
            error: function () {
                alert('Gagal mendapatkan kategori mesin.');
            }
        });
    } else {
        $('#vs-field').hide();
        $('#stitch-field').hide();
        $('#berat-field').hide();
    }
});
JS;

$this->registerJs($script);
?>

