<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use yii\helpers\Url;


/** @var yii\web\View $this */
/** @var app\models\Gudang $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="gudang-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- Menambahkan tanggal menggunakan datepicker -->
    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>


    <?php
    $dataPost = ArrayHelper::map(\app\models\Barang::find()->asArray()->all(), 'barang_id', function ($model) {
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'];
    });
    echo $form->field($model, 'barang_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Barang', 'id' => 'barang_id']);
    ?>



    <?php
    // Menampilkan user_id dan username di satu text field (readonly)
    $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    echo $form->field($model, 'user_info')->textInput(['value' => $user_info, 'readonly' => true, 'label' => 'user']) ?>


    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <!-- <?= $form->field($model, 'tanggal')->textInput(['id' => 'tanggal']) ?> -->

    <?= $form->field($model, 'quantity_awal')->textInput(['id' => 'qty_awal', 'readonly' => true]) ?>

    <?= $form->field($model, 'quantity_masuk')->textInput(['id' => 'qty_masuk']) ?>

    <?= $form->field($model, 'quantity_keluar')->textInput(['id' => 'qty_keluar']) ?>

    <?= $form->field($model, 'quantity_akhir')->textInput(['readonly' => true, 'id' => 'qty_akhir']) ?>

    <?= $form->field($model, 'area_gudang')->dropDownList(
        [   
            1 => 'Depan',
            2 => 'Bawah Tangga',
            3 => 'Lantai Dua',
            4 => 'Area Produksi',
            5 => 'Garasi (Barang Jadi)'
        ],
        ['prompt' => 'Pilih Area Gudang', 'id' => 'area_gudang']
    ) ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'update_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['gudang/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerJs("
        function calculateQtyAkhir() {
            var quantity_masuk = parseFloat($('#qty_masuk').val()) || 0;
            var quantity_keluar = parseFloat($('#qty_keluar').val()) || 0;
            var quantity_awal = parseFloat($('#qty_awal').val()) || 0;
            var quantity_akhir = quantity_awal+quantity_masuk-quantity_keluar;
            $('#qty_akhir').val(quantity_akhir);
        }

        $('#qty_masuk, #qty_keluar').on('input', calculateQtyAkhir);
    ");
    ?>

    <?php
    $urlGetStock = Url::to(['gudang/get-stock']);
    $this->registerJs("
        $('#barang_id').change(function() {
            var barang_id = $(this).val();
            var url = '$urlGetStock';
            
            // Debug log untuk memastikan URL dan barang_id
            console.log('Request URL: ' + url);
            console.log('Barang ID: ' + barang_id);
            
            // Mengirimkan request AJAX ke controller
            $.post(url, { barang_id: barang_id }, function(data) {
                if (data.quantity_akhir !== null) {
                    // Jika quantity_akhir ada, masukkan ke field stock
                    $('#qty_awal').val(data.quantity_akhir);
                } else {
                    // Jika tidak ada stock, kosongkan atau beri notifikasi
                    $('#qty_awal').val(0);
                    console.warn('Stock tidak ditemukan untuk barang_id ' + barang_id);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Handle error pada request AJAX
                console.error('AJAX error: ', textStatus, errorThrown);
                console.log(jqXHR.responseText); // Debug response text
            });
        });
    ");
    ?>

</div>