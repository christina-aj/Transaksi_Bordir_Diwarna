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

</div>
