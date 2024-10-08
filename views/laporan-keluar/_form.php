<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\laporanproduksi;

/** @var yii\web\View $this */
/** @var app\models\LaporanKeluar $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pc-content">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    
    $dataNama = LaporanProduksi::find()
    ->select(['nama_kerjaan'])
    ->distinct()
    ->asArray()
    ->all();

    $uniqueNama = ArrayHelper::map($dataNama, 'nama_kerjaan', 'nama_kerjaan');

    echo $form->field($model, 'nama')
        ->dropDownList(
            $uniqueNama,
            ['prompt'=>'Select Nama Kerjaan']
        );
    ?>
    
    <?php

    $dataBarang = LaporanProduksi::find()
    ->select(['nama_barang'])
    ->distinct()
    ->asArray()
    ->all();

    $uniqueBarang = ArrayHelper::map($dataBarang, 'nama_barang', 'nama_barang');

    echo $form->field($model, 'barang')
        ->dropDownList(
            $uniqueBarang,
            ['prompt'=>'Select Nama Barang']
        );
    ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'tanggal')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'catatan')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['laporan-keluar/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
