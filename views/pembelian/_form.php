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

    <!-- <?= $form->field($model, 'pembelian_id')->textInput() ?> -->

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
            'format' => 'yyyy-mm-dd',
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

    <?= $form->field($model, 'total_biaya')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kode_struk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'langsung_pakai')->checkbox(['label' => 'Langsung pakai'], true); ?>

    <!-- <?= $form->field($model, 'langsung_pakai')->textInput() ?> -->


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pembelian/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>