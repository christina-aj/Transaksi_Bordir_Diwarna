<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $pelanggan */
/** @var array $barangList */
/** @var array $unitList */

$this->title = 'Create Produk Custom Pelanggan';
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggan', 'url' => ['master-pelanggan/index']];
$this->params['breadcrumbs'][] = ['label' => $pelanggan->nama_pelanggan, 'url' => ['master-pelanggan/view', 'pelanggan_id' => $pelanggan->pelanggan_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card table-card p-4">
    <!-- <div class="card table-card p-4"> -->
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="d-flex mb-4">
            <p class="mt-2 me-4 mb-0">
                <strong>Kode Pelanggan:</strong> <?= Html::encode($pelanggan->kode_pelanggan) ?>
            </p>
            <p class="mt-2 mb-0">
                <strong>Nama Pelanggan:</strong> <?= Html::encode($pelanggan->nama_pelanggan) ?>
            </p>
        </div>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= Yii::$app->session->getFlash('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang Custom</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="product-rows">
                    <!-- Produk akan ditambahkan via JS -->
                </tbody>
            </table>

            <!-- <div class="mb-3">
                <button type="button" class="btn btn-success" onclick="addProductRow()">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div> -->

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <?= Html::a('Back', ['master-pelanggan/view', 'pelanggan_id' => $pelanggan->pelanggan_id], ['class' => 'btn btn-secondary']) ?>
            </div>
        </form>
    <!-- </div> -->
</div>

<?php
// Data untuk JS
$unitListJson = json_encode($unitList);
$barangListJson = json_encode(array_map(function($item) {
    return ['nama' => $item['nama'], 'unit_id' => $item['unit_id']];
}, $barangList));

$this->registerJs("
const unitList = $unitListJson;
const barangList = $barangListJson;
const barangIds = " . json_encode(array_keys($barangList)) . ";
let productCounter = 0;

// Update satuan berdasarkan barang yang dipilih
function updateSatuan(selectEl, productIdx, bomIdx) {
    const unitId = selectEl.options[selectEl.selectedIndex].dataset.unit;
    const satuanInput = document.getElementById('satuan-' + productIdx + '-' + bomIdx);
    if (unitId && unitList[unitId]) {
        satuanInput.value = unitList[unitId];
    } else {
        satuanInput.value = '';
    }
}

// Tambah produk baru
function addProductRow() {
    const tbody = document.getElementById('product-rows');
    const newIndex = productCounter;
    
    let barangOptions = '<option value=\"\">Pilih Bahan</option>';
    barangIds.forEach(function(barangId) {
        barangOptions += '<option value=\"' + barangId + '\" data-unit=\"' + barangList[barangId].unit_id + '\">' + barangList[barangId].nama + '</option>';
    });
    
    const newRow = `
        <tr data-product-index=\"` + newIndex + `\">
            <td class=\"text-center\">` + (newIndex + 1) + `</td>
            <td>
                <input type=\"text\" name=\"products[` + newIndex + `][kode_barang]\" class=\"form-control form-control-sm\" placeholder=\"PTR-12\">
            </td>
            <td>
                <input type=\"text\" name=\"products[` + newIndex + `][nama_barang]\" class=\"form-control form-control-sm\" placeholder=\"Kaos Kaki coklat Cap budi\">
            </td>
            <td class=\"text-center\">
                <button class=\"btn btn-sm btn-info\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#bom-` + newIndex + `\">
                    BOM
                </button>
                <button class=\"btn btn-sm btn-success\" type=\"button\" onclick=\"addProductRow()\">
                    +
                </button>
                <button class=\"btn btn-sm btn-danger\" type=\"button\" onclick=\"removeProductRow(this)\">
                    -
                </button>
            </td>
        </tr>
        <tr class=\"collapse\" id=\"bom-` + newIndex + `\" data-product-index=\"` + newIndex + `\">
            <td colspan=\"4\">
                <div class=\"p-2 border rounded bg-light\">
                    <h6 class=\"mb-2\">Detail BOM - Produk #` + (newIndex + 1) + `</h6>
                    <table class=\"table table-sm table-bordered mb-0\" id=\"bom-table-` + newIndex + `\">
                        <thead class=\"table-light\">
                            <tr>
                                <th style=\"width:40px;\">No</th>
                                <th>Bahan</th>
                                <th style=\"width:150px;\">Kuantitas</th>
                                <th style=\"width:120px;\">Satuan</th>
                                <th style=\"width:80px;\">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class=\"bom-row\">
                                <td class=\"text-center\">1</td>
                                <td>
                                    <select name=\"products[` + newIndex + `][bom][0][barang_id]\" class=\"form-select form-select-sm\" onchange=\"updateSatuan(this, ` + newIndex + `, 0)\">
                                        ` + barangOptions + `
                                    </select>
                                </td>
                                <td>
                                    <input type=\"number\" name=\"products[` + newIndex + `][bom][0][qty]\" class=\"form-control form-control-sm\" step=\"0.01\" min=\"0\" placeholder=\"1\">
                                </td>
                                <td>
                                    <input type=\"text\" id=\"satuan-` + newIndex + `-0\" class=\"form-control form-control-sm\" readonly placeholder=\"Meter\">
                                </td>
                                <td class=\"text-center\">
                                    <button type=\"button\" class=\"btn btn-sm btn-success\" onclick=\"addBomRow(` + newIndex + `)\">+</button>
                                    <button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"removeBomRow(` + newIndex + `)\">-</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', newRow);
    productCounter++;
    updateProductNumbers();
}

// Hapus produk
function removeProductRow(btn) {
    const rows = document.getElementById('product-rows').querySelectorAll('tr');
    if (rows.length <= 2) {
        alert('Minimal harus ada 1 produk!');
        return;
    }
    
    if (confirm('Hapus produk ini?')) {
        const tr = btn.closest('tr');
        const productIndex = tr.dataset.productIndex;
        
        // Hapus row produk dan row BOM
        const bomRow = document.querySelector('tr[data-product-index=\"' + productIndex + '\"].collapse');
        if (bomRow) bomRow.remove();
        tr.remove();
        
        updateProductNumbers();
    }
}

// Update nomor urut produk
function updateProductNumbers() {
    const rows = document.querySelectorAll('#product-rows > tr:not(.collapse)');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// Tambah row BOM baru
function addBomRow(productIdx) {
    const tbody = document.querySelector('#bom-table-' + productIdx + ' tbody');
    const rowCount = tbody.querySelectorAll('tr').length;
    const newIndex = rowCount;
    
    const newRow = tbody.querySelector('tr').cloneNode(true);
    
    // Update nomor urut
    newRow.querySelector('td:first-child').textContent = newIndex + 1;
    
    // Update name attributes
    newRow.querySelectorAll('select, input').forEach(el => {
        if (el.name) {
            el.name = el.name.replace(/\\[bom\\]\\[\\d+\\]/, '[bom][' + newIndex + ']');
        }
        if (el.id) {
            el.id = 'satuan-' + productIdx + '-' + newIndex;
        }
        el.value = '';
        if (el.tagName === 'SELECT') {
            el.selectedIndex = 0;
        }
    });
    
    // Update onchange handler
    const select = newRow.querySelector('select');
    select.setAttribute('onchange', 'updateSatuan(this, ' + productIdx + ', ' + newIndex + ')');
    
    tbody.appendChild(newRow);
}

// Hapus row BOM terakhir
function removeBomRow(productIdx) {
    const tbody = document.querySelector('#bom-table-' + productIdx + ' tbody');
    const rows = tbody.querySelectorAll('tr');
    
    if (rows.length > 1) {
        rows[rows.length - 1].remove();
    } else {
        alert('Minimal harus ada 1 bahan!');
    }
}

// Tambahkan 1 produk default saat halaman load
$(document).ready(function() {
    addProductRow();
});
", \yii\web\View::POS_END);
?>