<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'pembelian_id')->textInput(['id' => 'pembelian_id']) ?> -->

    <?php
    $dataPost = ArrayHelper::map(
        \app\models\User::find()->asArray()->all(),
        'user_id',
        function ($model) {
            return $model['user_id'] . ' - ' . $model['nama_pengguna'];
        }
    );
    echo $form->field($model, 'user_id')
        ->dropDownList(
            $dataPost,
            ['user_id' => 'nama_pengguna']
        );
    ?>
    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'yyyy-mm-dd'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-M-yyyy',
        ],
    ]); ?>
    <!-- <?= $form->field($model, 'tanggal')->textInput() ?> -->


    <?php
    $dataPost = ArrayHelper::map(\app\models\Supplier::find()->asArray()->all(), 'supplier_id', function ($model) {
        return $model['supplier_id'] . ' - ' . $model['nama'];
    });
    echo $form->field($model, 'supplier_id')
        ->dropDownList(
            $dataPost,
            ['supplier_id' => 'nama']
        );
    ?>

    <!-- <?= $form->field($model, 'supplier_id')->textInput() ?> -->

    <?= $form->field($model, 'total_biaya')->textInput(['id' => 'total_biaya', 'maxlength' => true, 'readonly' => true, 'value' => $model->total_biaya ?? 0]) ?>

    <?= $form->field($model, 'kode_struk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'langsung_pakai')->checkbox(['label' => 'Langsung pakai'], true); ?>

    <!-- <?= $form->field($model, 'langsung_pakai')->textInput() ?> -->


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pembelian/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <?php
    $script = <<< JS
// Fungsi untuk menghitung total biaya secara dinamis
function calculateTotalBiaya(pembelianId) {
    $.ajax({
        url: 'index.php?r=pembelian/calculate-total-biaya', // URL untuk menghitung total biaya
        type: 'GET',
        data: {pembelian_id: pembelianId},
        success: function(data) {
            // Update field total biaya di form
            $('#total-biaya').val(data.total_biaya);
        }
    });
}

// Ketika pengguna menambahkan atau mengedit rincian pembelian
$('#pembelian-detail-container').on('change', 'input', function() {
    var pembelianId = $('#pembelian-id').val(); // Ambil pembelian_id dari form
    calculateTotalBiaya(pembelianId); // Panggil fungsi untuk update total biaya
});
JS;
    $this->registerJs($script);
    ?>


</div>