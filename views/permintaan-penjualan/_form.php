<?php

use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
use yii\helpers\ArrayHelper;
use app\models\BarangProduksi;

echo Dialog::widget();

// Hitung total item
// $totalItem = count($modelDetails);
// ?>


<div class="permintaan-penjualan-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3 my-1">
            <div class="col-md-6">
                <strong>Tanggal:</strong> 
                <?= Yii::$app->formatter->asDate($modelPermintaan->tanggal_permintaan ?: date('Y-m-d'), 'php:d M Y') ?>
            </div>
        </div>

        <hr>

        <div class="card-body mx-4">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($modelPermintaan, 'nama_pelanggan')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <?= $form->field($modelPermintaan, 'tanggal_permintaan')->hiddenInput(['value' => date('Y-m-d')])->label(false) ?>

            <h3>Detail Permintaan</h3>

            <?php
            $barangList = ArrayHelper::map(BarangProduksi::find()->all(), 'barang_produksi_id', 'nama');
            ?>

            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper',
                'widgetBody' => '.container-items',
                'widgetItem' => '.item',
                'limit' => 50,
                'min' => 1,
                'insertButton' => '.add-item',
                'deleteButton' => '.remove-item',
                'model' => $modelDetails[0],
                'formId' => 'dynamic-form',
                'formFields' => ['barang_produksi_id', 'qty_permintaan', 'catatan'],
            ]); ?>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40%;">Nama Barang Produksi</th>
                        <th style="width: 15%;">Qty Permintaan</th>
                        <th style="width: 30%;">Catatan</th>
                        <th style="width: 15%; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="container-items">
                    <?php foreach ($modelDetails as $i => $modelPermintaanDetail): ?>
                        <tr class="item">
                            <td>
                                <?= $form->field($modelPermintaanDetail, "[{$i}]barang_produksi_id", ['template' => "{input}\n{error}"])
                                    ->dropDownList($barangList, [
                                        'prompt' => 'Pilih Barang Produksi...',
                                        'class' => 'form-control'
                                    ]) ?>
                            </td>

                            <td>
                                <?= $form->field($modelPermintaanDetail, "[{$i}]qty_permintaan", ['template' => "{input}\n{error}"])
                                    ->textInput(['type' => 'number', 'min' => 1]) ?>
                            </td>

                            <td>
                                <?= $form->field($modelPermintaanDetail, "[{$i}]catatan", ['template' => "{input}\n{error}"])
                                    ->textInput() ?>
                            </td>

                            <td style="text-align:center;">
                                <button type="button" class="remove-item btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button type="button" class="add-item btn btn-success btn-sm" title="Tambah">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php DynamicFormWidget::end(); ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                
                <?php if ($modelPermintaan->isNewRecord): ?>
                    <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-danger']) ?>
                <?php else: ?>
                    <?= Html::a('Back', ['view', 'permintaan_penjualan_id' => $modelPermintaan->permintaan_penjualan_id], ['class' => 'btn btn-secondary']) ?>
                <?php endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    // Update total item count
    function updateTotalItem() {
        var total = $('.container-items .item').length;
        $('#total-item').text(total);
    }

    // Initialize Select2 for dynamically added rows
    $('.dynamicform_wrapper').on('afterInsert', function(e, item) {
        // Initialize Select2 for the new row
        $(item).find('.select2-barang').select2({
            allowClear: true,
            placeholder: 'Pilih Barang Produksi...',
            width: '100%'
        });
        
        updateTotalItem();
    });

    // Update count when row is deleted
    $('.dynamicform_wrapper').on('afterDelete', function(e) {
        updateTotalItem();
    });

    // Initial count
    updateTotalItem();
");
?>

<style>
.select2-container {
    width: 100% !important;
}
</style>