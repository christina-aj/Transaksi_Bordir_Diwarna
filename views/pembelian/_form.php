<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Menamapilkan pembelian_id tetapi untuk saat ini tidak menggunakan  -->
    <!-- <?= $form->field($model, 'pembelian_id')->textInput(['id' => 'pembelian_id']) ?> -->

    <!-- Memasukan ID username ke database dalam bentuk form yang terhidden -->
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>


    <?php
    // Menampilkan user_id dan username di satu text field (readonly)
    $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    echo $form->field($model, 'user_info')->textInput(['value' => $user_info, 'readonly' => true, 'label' => 'user']) ?>


    <!-- Menambahkan tanggal menggunakan datepicker -->
    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>


    <!-- Menampilkan supplier dalam bentuk dropdown list -->
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

    <?php
    $urlGetUsername = Url::to(['penggunaan/get-user-info']);
    $this->registerJs("
        $(document).ready(function() {
            // Mengirimkan request AJAX untuk mendapatkan informasi user yang sedang login
            $.ajax({
                url: '$urlGetUsername', // Sesuaikan dengan URL action controller
                type: 'GET',
                success: function(data) {
                    if (data.success) {
                        // Mengisi nama user dan email di form atau tempat yang diinginkan
                        $('#username').text(data.username);
                        $('#user-id').val(data.user_id); // Jika ingin menyimpan user_id di form
                    } else {
                        console.error('Gagal mendapatkan data user: ' + data.message);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error pada AJAX request: ' + textStatus + ' ' + errorThrown);
                }
            });
        });
    ");
    ?>


</div>