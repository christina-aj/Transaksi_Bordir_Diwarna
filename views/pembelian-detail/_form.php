<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetail $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pembelian-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $dataPost = ArrayHelper::map(\app\models\Pembelian::find()->asArray()->all(), 'pembelian_id', function ($model) {
        return $model['pembelian_id'] . ' - ' . $model['kode_struk'];
    });
    echo $form->field($model, 'pembelian_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Kode Struk']);
    ?>

    <?php
    $dataPost = ArrayHelper::map(\app\models\Barang::find()->asArray()->all(), 'barang_id', function ($model) {
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'];
    });
    echo $form->field($model, 'barang_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Barang', 'id' => 'barang_id']);
    ?>

    <?= $form->field($model, 'harga_barang')->textInput(['id' => 'harga_barang', 'maxlength' => true]) ?>

    <?= $form->field($model, 'quantity_barang')->textInput(['id' => 'quantity_barang']) ?>

    <?= $form->field($model, 'total_biaya')->textInput(['id' => 'total_biaya', 'maxlength' => true, 'readonly' => true]) ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'langsung_pakai')->checkbox(['label' => 'Langsung pakai']) ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'updated_at')->textInput() ?> -->
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pembelian-detail/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerJs("
        function calculateTotal() {
            var quantity = parseFloat($('#quantity_barang').val()) || 0;
            var unitPrice = parseFloat($('#harga_barang').val()) || 0;
            var totalPrice = quantity * unitPrice;
            $('#total_biaya').val(totalPrice);
        }

        $('#quantity_barang, #harga_barang').on('input', calculateTotal);
    ");
    ?>

    <?php
    //     $urlGetHarga = Url::to(['pembelian-detail/get-harga']);
    //     $this->registerJs("
    // $('#barang_id').change(function() {
    //     var barang_id = $(this).val();
    //     var url = '$urlGetHarga';
    //     console.log('Request URL: ' + url); // Debug URL
    //     console.log('Barang ID: ' + barang_id); // Debug parameter

    //     $.post(url, { barang_id: barang_id }, function(data) {
    //         $('#harga_barang').val(data);
    //     }).fail(function(jqXHR, textStatus, errorThrown) {
    //         console.error('AJAX error: ', textStatus, errorThrown);
    //         console.log(jqXHR.responseText); // Debug response text
    //     });
    // });
    // ");
    ?>


</div>