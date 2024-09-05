<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="penggunaan-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tanggal_digunakan')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>
    <!-- <?= $form->field($model, 'barang_id')->textInput() ?> -->


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
    <?php
    $dataPost = ArrayHelper::map(\app\models\User::find()->asArray()->all(), 'user_id', function ($model) {
        return $model['user_id'] . ' - ' . $model['nama_pengguna'];
    });
    echo $form->field($model, 'user_id')
        ->dropDownList(
            $dataPost,
            ['prompt' => 'Pilih Pengguna', 'user_id' => 'nama_pengguna', 'id' => 'user_id']
        );
    ?>

    <?= $form->field($model, 'jumlah_digunakan')->textInput() ?>

    <!-- <?= $form->field($model, 'tanggal_digunakan')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['penggunaan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>