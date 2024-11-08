<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */

$this->title = 'Detail Pemesanan Kode: ' . $model->getFormattedOrderId();
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>

    <?php foreach ($modelsDetail as $index => $model): ?>
        <h3>Item #<?= $index + 1 ?></h3>
        <?= $form->field($model, "[$index]pemesanan_id")->textInput(['readonly' => true]) ?>
        <?= $form->field($model, "[$index]barang_id")->textInput(['readonly' => true]) ?>
        <?= $form->field($model, "[$index]qty")->textInput(['readonly' => true]) ?>
        <?= $form->field($model, "[$index]qty_terima")->textInput(['required' => true]) ?>
        <?= $form->field($model, "[$index]catatan")->textInput() ?>
        <?= $form->field($model, "[$index]langsung_pakai")->checkbox(['disabled' => true]) ?>
        <?= Html::activeHiddenInput($model, "[$index]langsung_pakai") ?>
        <!-- Is Correct Checkbox (hidden in create mode) -->
        <?= $form->field($model, "[$index]is_correct")->checkbox(['label' => 'Barang Sesuai']) ?>
        <?= Html::activeHiddenInput($model, "[$index]pesandetail_id") ?> <!-- hidden input untuk ID -->
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton('Save All', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pemesanan/view', 'pemesanan_id' => $pemesananId], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>