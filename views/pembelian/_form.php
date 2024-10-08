<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pembelian $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pemesanan_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'total_biaya')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
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
