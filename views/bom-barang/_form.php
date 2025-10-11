<?php

use app\models\Barang;
use app\models\BarangProduksi;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;

/** @var yii\web\View $this */
/** @var app\models\BomBarang $modelBom */
/** @var app\models\BomDetail[] $modelDetails */

$isUpdate = !$modelDetails[0]->isNewRecord;
?>

<div class="bom-barang-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="card-body mx-4">
            <?php $form = ActiveForm::begin([
                'id' => 'bom-form',
                'method' => 'post',
            ]); ?>

            <!-- Pilih Barang Produksi -->
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Barang Produksi <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <?= Select2::widget([
                        'name' => 'BomBarang[barang_produksi_id]',
                        'value' => $modelBom->barang_produksi_id,
                        'data' => ArrayHelper::map(BarangProduksi::find()->all(), 'barang_produksi_id', 'nama'),
                        'options' => [
                            'placeholder' => 'Pilih Barang Produksi...',
                            'id' => 'barang-produksi-select',
                            'required' => true
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]); ?>
                </div>
            </div>

            <hr>
            <h3>Detail Bahan Baku</h3>

            <!-- Tabel Detail BOM -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 10%;">Kode Barang</th>
                            <th style="width: 35%;">Nama Barang <span class="text-danger">*</span></th>
                            <th style="width: 15%;">Jumlah <span class="text-danger">*</span></th>
                            <th style="width: 25%;">Catatan</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <?php foreach ($modelDetails as $index => $modelDetail): ?>
                            <tr>
                                <?= Html::hiddenInput("BomDetail[$index][BOM_detail_id]", $modelDetail->BOM_detail_id) ?>
                                
                                <td class="text-center row-number"><?= $index + 1 ?></td>
                                
                                <td>
                                    <?= Html::textInput("BomDetail[$index][kode_barang]", $modelDetail->kode_barang ?? '', [
                                        'class' => 'form-control',
                                        'readonly' => true,
                                        'id' => "bomdetail-{$index}-kode_barang"
                                    ]) ?>
                                </td>
                                
                                <td>
                                    <?= Html::hiddenInput("BomDetail[$index][barang_id]", $modelDetail->barang_id, [
                                        'id' => "bomdetail-{$index}-barang_id"
                                    ]) ?>
                                    
                                    <?= Typeahead::widget([
                                        'name' => "BomDetail[$index][nama_barang]",
                                        'value' => $modelDetail->nama_barang ?? '',
                                        'options' => [
                                            'placeholder' => 'Cari Nama Barang...',
                                            'id' => "bomdetail-{$index}-nama_barang",
                                            'class' => 'form-control',
                                        ],
                                        'pluginOptions' => ['highlight' => true],
                                        'scrollable' => true,
                                        'dataset' => [
                                            [
                                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                                'display' => 'value',
                                                'templates' => [
                                                    'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                                                    'suggestion' => new \yii\web\JsExpression('function(data) {
                                                        if (data.barang_id && data.kode_barang && data.nama_barang) {
                                                            return "<div>" + data.kode_barang + " - " + data.nama_barang + "</div>";
                                                        } else {
                                                            return "<div>Barang tidak ditemukan</div>";
                                                        }
                                                    }')
                                                ],
                                                'remote' => [
                                                    'url' => Url::to(['bom-detail/search']) . '?q=%QUERY',
                                                    'wildcard' => '%QUERY',
                                                ],
                                            ]
                                        ],
                                        'pluginEvents' => [
                                            "typeahead:select" => new \yii\web\JsExpression("function(event, suggestion) {
                                                $('#bomdetail-{$index}-barang_id').val(suggestion.barang_id);
                                                $('#bomdetail-{$index}-kode_barang').val(suggestion.kode_barang);
                                            }")
                                        ]
                                    ]); ?>
                                </td>
                                
                                <td>
                                    <?= Html::textInput("BomDetail[$index][qty_BOM]", $modelDetail->qty_BOM, [
                                        'class' => 'form-control',
                                        'type' => 'number',
                                        'min' => 1,
                                        'required' => true,
                                        'placeholder' => 'Jumlah'
                                    ]) ?>
                                </td>
                                
                                <td>
                                    <?= Html::textarea("BomDetail[$index][catatan]", $modelDetail->catatan, [
                                        'class' => 'form-control',
                                        'rows' => 1,
                                        'placeholder' => 'Catatan'
                                    ]) ?>
                                </td>
                                
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-success btn-sm add-row" title="Tambah">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete-row" data-id="<?= $modelDetail->BOM_detail_id ?>" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Hidden input untuk menyimpan ID detail yang akan dihapus -->
            <?= Html::hiddenInput('deleteRows', '', ['id' => 'deleteRows']) ?>

            <div class="form-group mt-3">
                <?= Html::submitButton('<i class="fas fa-save"></i> Simpan', ['class' => 'btn btn-success', 'id' => 'saveButton']) ?>

                <?php if (!$isUpdate): ?>
                    <!-- Mode Create: Tombol Cancel -->
                    <?= Html::a('<i class="fas fa-times"></i> Batal', [
                        'cancel',
                        'BOM_barang_id' => $modelBom->BOM_barang_id,
                    ], [
                        'id' => 'cancelButton',
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Apakah Anda yakin ingin membatalkan BOM ini?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php else: ?>
                    <!-- Mode Update: Tombol Back -->
                    <?= Html::a('<i class="fas fa-arrow-left"></i> Kembali', ['view', 'BOM_barang_id' => $modelBom->BOM_barang_id], [
                        'class' => 'btn btn-secondary',
                    ]) ?>
                <?php endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$BomBarangId = $modelBom->BOM_barang_id;
$isNewRecord = !$isUpdate;
$searchUrl = Url::to(['bom-detail/search']);

$this->registerJs("
var rowIndex = " . count($modelDetails) . ";
var BomBarangId = " . json_encode($BomBarangId) . ";
var isNewRecord = " . json_encode($isNewRecord) . ";

// Fungsi untuk menambah baris baru
$(document).on('click', '.add-row', function() {
    var newRow = `
        <tr>
            <td class='text-center row-number'></td>
            <td>
                <input type='text' name='BomDetail[\${rowIndex}][kode_barang]' id='bomdetail-\${rowIndex}-kode_barang' class='form-control' readonly>
            </td>
            <td>
                <input type='hidden' name='BomDetail[\${rowIndex}][barang_id]' id='bomdetail-\${rowIndex}-barang_id'>
                <input type='text' name='BomDetail[\${rowIndex}][nama_barang]' id='bomdetail-\${rowIndex}-nama_barang' class='form-control typeahead-input' data-index='\${rowIndex}' placeholder='Cari Nama Barang...'>
            </td>
            <td>
                <input type='number' name='BomDetail[\${rowIndex}][qty_BOM]' class='form-control' min='1' required placeholder='Jumlah'>
            </td>
            <td>
                <textarea name='BomDetail[\${rowIndex}][catatan]' class='form-control' rows='1' placeholder='Catatan'></textarea>
            </td>
            <td class='text-center'>
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
    initializeTypeahead('#bomdetail-' + rowIndex + '-nama_barang', rowIndex);
    updateRowNumbers();
    rowIndex++;
    toggleAddDeleteButtons();
});

// Fungsi untuk menghapus baris
$(document).on('click', '.delete-row', function() {
    var id = $(this).data('id');
    if (id) {
        var deleteRows = $('#deleteRows').val() ? JSON.parse($('#deleteRows').val()) : [];
        deleteRows.push(id);
        $('#deleteRows').val(JSON.stringify(deleteRows));
    }
    $(this).closest('tr').remove();
    updateRowNumbers();
    toggleAddDeleteButtons();
});

// Fungsi untuk menginisialisasi typeahead pada input baru
function initializeTypeahead(selector, index) {
    $(selector).typeahead({
        highlight: true,
        minLength: 1
    },
    {
        name: 'barang',
        display: 'nama_barang',
        limit: 10,
        source: new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama_barang'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                // url: '" . $searchUrl . "?q=%QUERY',
                url: '" . Url::to(['bom-detail/search']) . "?q=%QUERY',
                wildcard: '%QUERY'
            }
        }),
        templates: {
            notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
            suggestion: function(data) {
                return `<div>\${data . kode_barang} - \${data . nama_barang}</div>`;
            }
        }
    }).bind('typeahead:select', function(ev, suggestion) {
        $(`#bomdetail-\${index}-barang_id`).val(suggestion.barang_id);
        $(`#bomdetail-\${index}-kode_barang`).val(suggestion.kode_barang);
    });

    // Menambahkan placeholder pada input setelah typeahead diinisialisasi
    $(selector).attr('placeholder', 'Cari Nama Barang...');
}

// Fungsi untuk menampilkan/menyembunyikan tombol add dan delete
function toggleAddDeleteButtons() {
    var rows = $('#table-body tr');
    var rowCount = rows.length;

    if (rowCount > 1) {
        $('.delete-row').show();
    } else {
        $('.delete-row').hide();
    }

    $('.add-row').hide();
    $('#table-body tr:last-child .add-row').show();
}

// Validasi form sebelum navigate (hanya untuk create)
var formChanged = true;

$(document).ready(function() {
    toggleAddDeleteButtons();
    
    // Cegah navigasi jika form belum disimpan (hanya mode create)
    $('button, a').on('click', function(e) {
        if (formChanged && isNewRecord && !$(e.currentTarget).is('#cancelButton, #saveButton, .add-row, .delete-row')) {
            e.preventDefault();
            alert('Selesaikan form dahulu atau batalkan BOM ini.');
        }
    });
    
    // Tandai form sudah disimpan saat submit
    $('#bom-form').on('submit', function() {
        formChanged = false;
    });
});
");
?>

<style>
.tt-menu {
    max-height: 200px;
    overflow-y: auto;
}

.modal {
    z-index: 1051;
}

.modal-backdrop {
    z-index: 1050;
}
</style>