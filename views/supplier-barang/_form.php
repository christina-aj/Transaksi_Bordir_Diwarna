<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Barang;
use app\models\Supplier;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarang $model */
/** @var app\models\SupplierBarangDetail[] $supplierBarangDetails */

$isUpdate = !$model->isNewRecord;
?>

<div class="supplier-barang-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>

        <div class="card-body mx-4">
            
            <?php $form = ActiveForm::begin(['id' => 'supplier-form']); ?>

            <!-- Step 1: Pilih Barang -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Barang <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <?= $form->field($model, 'barang_id', ['template' => "{input}\n{error}"])->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(Barang::find()->all(), 'barang_id', 'nama_barang'),
                        'options' => [
                            'placeholder' => 'Pilih Barang ...',
                            'id' => 'barang-select',
                            'disabled' => $isUpdate // Disabled saat update
                        ],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
                </div>
            </div>

            <hr>
            <h3>Detail Supplier Barang</h3>

            <?php
            $supplierList = ArrayHelper::map(Supplier::find()->all(), 'supplier_id', 'nama');
            ?>

            <!-- Tabel Detail Supplier -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 30%;">Nama Supplier <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Lead Time (hari)</th>
                            <th style="width: 20%;">Harga per Kg</th>
                            <th style="width: 15%;">Biaya Pesan</th>
                            <th style="width: 15%;">Supplier Utama</th>
                            <th style="width: 15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <?php foreach ($supplierBarangDetails as $index => $detail): ?>
                            <tr>
                                <?= Html::hiddenInput("SupplierBarangDetail[$index][supplier_barang_detail_id]", $detail->supplier_barang_detail_id ?? '') ?>
                                
                                <td class="text-center row-number"><?= $index + 1 ?></td>
                                
                                <td>
                                    <?= $form->field($detail, "[{$index}]supplier_id", ['template' => "{input}\n{error}"])
                                        ->widget(Select2::classname(), [
                                            'data' => $supplierList,
                                            'options' => [
                                                'placeholder' => 'Pilih Supplier...',
                                                'id' => "supplier-{$index}",
                                            ],
                                            'pluginOptions' => ['allowClear' => true],
                                        ]) ?>
                                </td>

                                <td>
                                    <?= $form->field($detail, "[{$index}]lead_time", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 1, 'placeholder' => 'Hari']) ?>
                                </td>

                                <td>
                                    <?= $form->field($detail, "[{$index}]harga_per_kg", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01', 'placeholder' => 'Rp']) ?>
                                </td>

                                <td>
                                    <?= $form->field($detail, "[{$index}]biaya_pesan", ['template' => "{input}\n{error}"])
                                        ->textInput(['type' => 'number', 'min' => 0, 'step' => '0.01', 'placeholder' => 'Rp']) ?>
                                </td>

                                <td style="text-align:center;">
                                    <?= Html::radio("supp_utama_selected", $index == 0, [
                                        'value' => $index,
                                        'class' => 'radio-supp-utama',
                                        'id' => "radio_utama_{$index}",
                                        'data-index' => $index
                                    ]) ?>
                                    
                                    <?= Html::activeHiddenInput($detail, "[{$index}]supp_utama", [
                                        'class' => 'supp-utama-value',
                                        'value' => ($index == 0) ? 1 : 0
                                    ]) ?>
                                </td>

                                <td style="text-align:center;">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success btn-sm add-row" title="Tambah">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-row" data-id="<?= $detail->supplier_barang_detail_id ?? '' ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Hidden input untuk row yang dihapus -->
            <?= Html::hiddenInput('deleteRows', '', ['id' => 'deleteRows']) ?>

            <div class="form-group text-center mt-3">
                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success', 'id' => 'saveButton']) ?>

                <?php if (!$isUpdate): ?>
                    <!-- Tombol Batal untuk Create: redirect ke index, tidak pakai supplier_barang_id -->
                    <!-- <?= Html::a('<i class="fas fa-times"></i> Batal', ['index'], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Data belum disimpan akan dihapus. Lanjutkan?',
                        ],
                    ]) ?> -->
                    <a href="<?= \yii\helpers\Url::to(['index']) ?>" id="cancelButton" class="btn btn-danger" onclick="return confirmCancel();">
                        <i class="fas fa-times"></i> Batal
                    </a>
                <?php else: ?>
                    <!-- Tombol Kembali/Batal untuk Update: kirim supplier_barang_id ke cancel -->
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['view', 'supplier_barang_id' => $model->supplier_barang_id], [
                        'class' => 'btn btn-secondary',
                    ]) ?>
                <?php endif; ?>
            </div>

            <?php ActiveForm::end(); ?>




        </div>
    </div>
</div>

<?php
$supplierBarangId = $model->supplier_barang_id ?? 0;
$isNewRecord = !$isUpdate;

// Generate supplier options untuk JavaScript
$supplierOptions = '';
foreach ($supplierList as $id => $nama) {
    $supplierOptions .= "<option value='{$id}'>" . Html::encode($nama) . "</option>";
}

$this->registerJs("
var rowIndex = " . count($supplierBarangDetails) . ";
var isNewRecord = " . json_encode($isNewRecord) . ";
var supplierOptions = " . json_encode($supplierOptions) . ";

// Tambah baris baru
$(document).on('click', '.add-row', function() {
    var newRow = `
        <tr>
            <td class='text-center row-number'></td>
            <td>
                <select name='SupplierBarangDetail[\${rowIndex}][supplier_id]' id='supplier-\${rowIndex}' class='form-control supplier-select'>
                    <option value=''>Pilih Supplier...</option>
                    \${supplierOptions}
                </select>
            </td>
            <td>
                <input type='number' name='SupplierBarangDetail[\${rowIndex}][lead_time]' class='form-control' min='1' placeholder='Hari'>
            </td>
            <td>
                <input type='number' name='SupplierBarangDetail[\${rowIndex}][harga_per_kg]' class='form-control' min='0' step='0.01' placeholder='Rp'>
            </td>
            <td>
                <input type='number' name='SupplierBarangDetail[\${rowIndex}][biaya_pesan]' class='form-control' min='0' step='0.01' placeholder='Rp'>
            </td>
            <td style='text-align:center;'>
                <input type='radio' name='supp_utama_selected' value='\${rowIndex}' class='radio-supp-utama' id='radio_utama_\${rowIndex}' data-index='\${rowIndex}'>
                <input type='hidden' name='SupplierBarangDetail[\${rowIndex}][supp_utama]' class='supp-utama-value' value='0'>
            </td>
            <td style='text-align:center;'>
                <div class='btn-group' role='group'>
                    <button type='button' class='btn btn-success btn-sm add-row' title='Tambah'>
                        <i class='fas fa-plus'></i>
                    </button>
                    <button type='button' class='btn btn-danger btn-sm delete-row' title='Hapus'>
                        <i class='fas fa-trash'></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
    $('#table-body').append(newRow);
    
    // Init Select2 untuk row baru
    $('#supplier-' + rowIndex).select2({
        allowClear: true,
        placeholder: 'Pilih Supplier...',
        width: '100%'
    });
    
    updateRowNumbers();
    toggleButtons();
    rowIndex++;
});

// Hapus baris
$(document).on('click', '.delete-row', function() {
    var id = $(this).data('id');
    if (id) {
        var deleteRows = $('#deleteRows').val() ? JSON.parse($('#deleteRows').val()) : [];
        deleteRows.push(id);
        $('#deleteRows').val(JSON.stringify(deleteRows));
    }
    $(this).closest('tr').remove();
    
    // Jika tidak ada yang checked, check yang pertama
    if ($('.radio-supp-utama:checked').length === 0 && $('#table-body tr').length > 0) {
        $('.radio-supp-utama:first').prop('checked', true);
        $('.supp-utama-value:first').val(1);
    }
    
    updateRowNumbers();
    toggleButtons();
});

// Update nomor urut
function updateRowNumbers() {
    $('#table-body tr').each(function(index) {
        $(this).find('.row-number').text(index + 1);
    });
}

// Toggle tombol add/delete
function toggleButtons() {
    var rows = $('#table-body tr');
    if (rows.length > 1) {
        $('.delete-row').show();
    } else {
        $('.delete-row').hide();
    }
    
    $('.add-row').hide();
    $('#table-body tr:last-child .add-row').show();
}

// Handle radio button supplier utama
$(document).on('change', '.radio-supp-utama', function() {
    $('.supp-utama-value').val(0);
    var selectedIndex = $(this).data('index');
    $('input[name=\"SupplierBarangDetail[' + selectedIndex + '][supp_utama]\"]').val(1);
});

// Validasi navigasi (hanya untuk create)
var formChanged = true;

$(document).ready(function() {
    toggleButtons();
    
    // Set radio pertama sebagai default
    if ($('.radio-supp-utama:checked').length === 0) {
        $('.radio-supp-utama:first').prop('checked', true);
        $('.supp-utama-value:first').val(1);
    }
    
    // Cegah navigasi jika form belum selesai (hanya mode create)
    $('button, a').on('click', function(e) {
        if (formChanged && isNewRecord && !$(e.currentTarget).is('#cancelButton, #saveButton, .add-row, .delete-row')) {
            e.preventDefault();
            alert('Selesaikan form dahulu atau batalkan data ini.');
        }
    });
    
    $('#supplier-form').on('submit', function() {
        formChanged = false;
    });
});
");
?>

<style>
.select2-container {
    width: 100% !important;
}
.table-card {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>