<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Stock $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="stock-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tambah_stock')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'yyyy-mm-dd'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ],
    ]); ?>

    <!-- <?= $form->field($model, 'tambah_stock')->textInput() ?> -->

    <?php
    $dataPost = ArrayHelper::map(\app\models\Barang::find()->asArray()->all(), 'barang_id', function ($model) {
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'];
    });
    echo $form->field($model, 'barang_id')
        ->dropDownList(
            $dataPost,
            ['prompt' => 'Pilih Barang', 'barang_id' => 'nama_barang', 'id' => 'barang_id']
        );
    ?>

    <!-- <?= $form->field($model, 'barang_id')->textInput() ?> -->

    <?= $form->field($model, 'quantity_awal')->textInput(['id' => 'qty_awal', 'readonly' => true]) ?>

    <?= $form->field($model, 'quantity_masuk')->textInput(['id' => 'qty_masuk']) ?>

    <?= $form->field($model, 'quantity_keluar')->textInput(['id' => 'qty_keluar']) ?>

    <?= $form->field($model, 'quantity_akhir')->textInput(['id' => 'qty_akhir', 'readonly' => true]) ?>

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
            ['prompt' => 'Pilih User', 'user_id' => 'nama_pengguna']
        );
    ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'is_ready')->checkbox(['label' => 'Apakah Ready?', 'class' => 'linked-checkbox'], true); ?>

    <?= $form->field($model, 'is_new')->checkbox(['label' => 'Apakah Baru?', 'class' => 'linked-checkbox'], true); ?>

    <!-- <?= $form->field($model, 'is_ready')->textInput() ?> -->

    <!-- <?= $form->field($model, 'is_new')->textInput() ?> -->

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['stock/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $this->registerJs("
    $('.linked-checkbox').on('change', function() {
    $('.linked-checkbox').not(this).prop('checked', false);
    });
    ");
    ?>

    <?php
    $this->registerJs("function calculateTotal() {
            var quantityAwal = parseFloat($('#qty_awal').val()) || 0;
            var quantityMasuk = parseFloat($('#qty_masuk').val()) || 0;
            var quantityKeluar = parseFloat($('#qty_keluar').val()) || 0;
            var quantityAkhir = quantityAwal + quantityMasuk - quantityKeluar;
            $('#qty_akhir').val(quantityAkhir);
        }

        $('#qty_awal, #qty_masuk, #qty_keluar' ).on('input', calculateTotal);
    
    ")
    ?>


    <?php
    $urlGetStock = \yii\helpers\Url::to(['stock/get-stock']);
    $this->registerJs("
$('#barang_id').change(function() {
    var barang_id = $(this).val();
    var url = '$urlGetStock';
    console.log('Request URL: ' + url); // Debug URL
    console.log('Barang ID: ' + barang_id); // Debug parameter
    
    $.post(url, { barang_id: barang_id }, function(data) {
        console.log('Received data: ', data); // Debug received data
        $('#qty_awal').val(data.quantity_awal); // Akses properti quantity_awal
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX error: ', textStatus, errorThrown);
        console.log(jqXHR.responseText); // Debug response text
    });
});
");
    ?>

</div>