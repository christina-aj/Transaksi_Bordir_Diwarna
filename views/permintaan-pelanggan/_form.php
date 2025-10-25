<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\Url;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPelanggan $model */
/** @var app\models\MasterPelanggan[] $pelangganList */
/** @var string $nextKode */
/** @var yii\widgets\ActiveForm $form */

$this->title = $model->isNewRecord ? 'Buat Permintaan Pelanggan' : 'Edit Permintaan Pelanggan';
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Pelanggan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
.item-row {
    border-bottom: 1px solid #dee2e6;
    padding: 15px 0;
}
.btn-remove-item {
    margin-top: 32px;
}
.tipe-btn-group .btn {
    min-width: 150px;
}
.tipe-btn-group .btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
");
?>

<div class="card table-card p-4">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-permintaan']); ?>

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="form-label">Kode Permintaan</label>
                <!-- <input type="text" class="form-control" value="<?= $nextKode ?>" disabled> -->
                <input type="text" class="form-control" value="<?= $nextKode ?? $model->kode_permintaan ?>" disabled>
            </div>
        </div>
        <div class="col-md-7">
            <?= $form->field($model, 'pelanggan_id')->dropDownList(
                \yii\helpers\ArrayHelper::map($pelangganList, 'pelanggan_id', 'nama_pelanggan'),
                ['prompt' => 'Pilih Pelanggan', 'id' => 'pelanggan-dropdown']
            ) ?>
        </div>
        
        <div class="col-md-3">
            <?= $form->field($model, 'tanggal_permintaan')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <label class="form-label">Tipe Pelanggan</label>
            <div class="btn-group tipe-btn-group" role="group">
                <button type="button" class="btn btn-outline-primary tipe-btn active" data-tipe="1">
                    Custom
                </button>
                <button type="button" class="btn btn-outline-primary tipe-btn" data-tipe="2">
                    Polosan Ready
                </button>
            </div>
            <input type="hidden" name="tipe_pelanggan" value="1" id="tipe-pelanggan-input">
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Detail Barang</h4>
        <button type="button" class="btn btn-success" id="btn-add-item">
            <i class="fas fa-plus"></i> Tambah Item
        </button>
    </div>

    <!-- <div id="items-container"> -->
        <!-- Item rows akan di-generate di sini -->
    <!-- </div> -->

    <div id="items-container">
        <?php if (!empty($detailModels)): ?>
            <?php foreach ($detailModels as $i => $detail): ?>
                <div class="item-row row" data-index="<?= $i ?>">
                    <div class="col-md-5">
                        <label>Barang</label>
                        <select name="PermintaanDetail[<?= $i ?>][<?= $detail->barang_custom_pelanggan_id ? 'barang_custom_pelanggan_id' : 'barang_produksi_id' ?>]" class="form-control barang-select" required>
                            <option value="">Pilih Barang</option>
                            <?php
                            // Ambil list barang tergantung tipe pelanggan
                            $listBarang = $detail->barang_custom_pelanggan_id
                                ? \yii\helpers\ArrayHelper::map($detail->barangCustomPelanggan::find()->all(), 'barang_custom_pelanggan_id', 'nama_barang_custom')
                                : \yii\helpers\ArrayHelper::map($detail->barangProduksi::find()->all(), 'barang_produksi_id', 'nama');
                            foreach ($listBarang as $id => $nama):
                            ?>
                                <option value="<?= $id ?>" <?= ($detail->barang_custom_pelanggan_id == $id || $detail->barang_produksi_id == $id) ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Jumlah</label>
                        <input type="number" name="PermintaanDetail[<?= $i ?>][qty_permintaan]" class="form-control" value="<?= $detail->qty_permintaan ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label>Deskripsi</label>
                        <input type="text" name="PermintaanDetail[<?= $i ?>][catatan]" class="form-control" value="<?= $detail->catatan ?>">
                    </div>

                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>


    <div class="form-group mt-4">
        <!-- <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?> -->
        <?= Html::submitButton(
            $model->isNewRecord ? 'Save' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$urlGetBarang = Url::to(['get-barang-by-pelanggan']);
$this->registerJs("
let itemIndex = 0;
let currentTipe = 1; // 1=custom, 2=polosan
let barangData = {};

// Handle tipe pelanggan toggle
$('.tipe-btn').on('click', function() {
    $('.tipe-btn').removeClass('active');
    $(this).addClass('active');
    currentTipe = $(this).data('tipe');
    $('#tipe-pelanggan-input').val(currentTipe);
    
    // Clear existing items
    $('#items-container').empty();
    itemIndex = 0;
    
    // Load barang based on new tipe
    loadBarangData();
});

// Handle pelanggan change
$('#pelanggan-dropdown').on('change', function() {
    $('#items-container').empty();
    itemIndex = 0;
    loadBarangData();
});

// Load barang data via AJAX
function loadBarangData() {
    const pelangganId = $('#pelanggan-dropdown').val();
    
    if (!pelangganId) {
        barangData = {};
        return;
    }
    
    $.ajax({
        url: '$urlGetBarang',
        type: 'GET',
        data: {
            pelanggan_id: pelangganId,
            tipe: currentTipe
        },
        success: function(response) {
            barangData = response;
        },
        error: function() {
            alert('Gagal memuat data barang');
        }
    });
}

// Add new item row
$('#btn-add-item').on('click', function() {
    const pelangganId = $('#pelanggan-dropdown').val();
    
    if (!pelangganId) {
        alert('Pilih pelanggan terlebih dahulu!');
        return;
    }
    
    if (Object.keys(barangData).length === 0) {
        alert('Produk ' + (currentTipe == 1 ? 'Custom' : 'Polosan Ready') + ' ID cannot be blank.');
        return;
    }
    
    addItemRow();
});

function addItemRow() {
    const row = $('<div>', {class: 'item-row row', 'data-index': itemIndex});
    
    // Barang dropdown
    const barangCol = $('<div>', {class: 'col-md-5'});
    const barangLabel = $('<label>').text('Barang');
    const barangSelect = $('<select>', {
        name: 'PermintaanDetail[' + itemIndex + '][barang_id]',
        class: 'form-control barang-select',
        required: true
    });
    
    barangSelect.append($('<option>', {value: '', text: 'Pilih Barang'}));
    
    $.each(barangData, function(id, nama) {
        barangSelect.append($('<option>', {value: id, text: nama}));
    });
    
    barangCol.append(barangLabel).append(barangSelect);
    
    // Jumlah input
    const jumlahCol = $('<div>', {class: 'col-md-2'});
    const jumlahLabel = $('<label>').text('Jumlah');
    const jumlahInput = $('<input>', {
        type: 'number',
        name: 'PermintaanDetail[' + itemIndex + '][qty_permintaan]',
        class: 'form-control',
        placeholder: '0',
        min: 1,
        required: true
    });
    jumlahCol.append(jumlahLabel).append(jumlahInput);
    
    // Deskripsi input
    const deskripsiCol = $('<div>', {class: 'col-md-4'});
    const deskripsiLabel = $('<label>').text('Deskripsi');
    const deskripsiInput = $('<input>', {
        type: 'text',
        name: 'PermintaanDetail[' + itemIndex + '][catatan]',
        class: 'form-control',
        placeholder: 'Catatan...'
    });
    deskripsiCol.append(deskripsiLabel).append(deskripsiInput);
    
    // Remove button
    const aksiCol = $('<div>', {class: 'col-md-1'});
    const removeBtn = $('<button>', {
        type: 'button',
        class: 'btn btn-danger btn-remove-item',
        html: '<i class=\"fas fa-trash\"></i>'
    });
    aksiCol.append(removeBtn);
    
    // Hidden input untuk tipe
    if (currentTipe == 1) {
        // Custom - barang_custom_pelanggan_id
        barangSelect.attr('name', 'PermintaanDetail[' + itemIndex + '][barang_custom_pelanggan_id]');
    } else {
        // Polosan - barang_produksi_id
        barangSelect.attr('name', 'PermintaanDetail[' + itemIndex + '][barang_produksi_id]');
    }
    
    row.append(barangCol).append(jumlahCol).append(deskripsiCol).append(aksiCol);
    $('#items-container').append(row);
    
    itemIndex++;
}

// Remove item row
$(document).on('click', '.btn-remove-item', function() {
    $(this).closest('.item-row').remove();
});

// Initial load
loadBarangData();
");
?>