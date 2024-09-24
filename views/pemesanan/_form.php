<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Barang;
use app\models\Unit;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pemesanan-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'barang_id')->textInput() ?> -->
    <?php
    $dataBarang = Barang::find()->asArray()->all();
    $dataUnit = Unit::find()->asArray()->all();

    $dataPost = ArrayHelper::map($dataBarang, 'barang_id', function ($model) use ($dataUnit) {
        // Cari satuan yang sesuai dengan barang ini
        $unit = ArrayHelper::getValue($dataUnit, array_search($model['unit_id'], array_column($dataUnit, 'unit_id')));

        // Jika ditemukan satuan, masukkan ke dalam output
        $unitName = $unit ? $unit['satuan'] : 'Satuan tidak ditemukan';

        // Format string dengan data dari Barang dan Satuan
        return $model['barang_id'] . ' - ' . $model['kode_barang'] . ' - ' . $model['nama_barang'] . ' - ' . $model['angka'] . ' ' . $unitName . ' - ' . $model['warna'];
    });
    echo $form->field($model, 'barang_id')
        ->dropDownList($dataPost, ['prompt' => 'Pilih Barang', 'id' => 'barang_id']);

    ?>
    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?php // Menampilkan user_id dan username di satu text field (readonly)
    $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    echo $form->field($model, 'user_info')->textInput(['value' => $user_info, 'readonly' => true, 'label' => 'user']) ?>

    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>
    <!-- <?= $form->field($model, 'tanggal')->textInput() ?> -->

    <?= $form->field($model, 'qty')->textInput() ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pemesanan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>