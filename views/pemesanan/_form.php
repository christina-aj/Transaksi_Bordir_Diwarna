<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pemesanan-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'user_id')->textInput() ?> -->
    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>


    <?php // Menampilkan user_id dan username di satu text field (readonly)
    $user_info = Yii::$app->user->id . ' - ' . Yii::$app->user->identity->nama_pengguna;
    echo $form->field($model, 'user_info')->textInput(['value' => $user_info, 'readonly' => true, 'label' => 'user']) ?>

    <!-- <?= $form->field($model, 'tanggal')->textInput() ?> -->

    <?= $form->field($model, 'tanggal')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'dd-mm-yyyy'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd-mm-yyyy',
        ],
    ]); ?>

    <?= $form->field($model, 'total_item')->textInput() ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'updated_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pemesanan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    $urlGetUsername = Url::to(['pemesanan/get-user-info']);
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