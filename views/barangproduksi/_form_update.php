<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */
/** @var array $barangList */
/** @var array $existingBom */

$this->title = 'Update Produk Jadi';
$this->params['breadcrumbs'][] = ['label' => 'Barang Produksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama, 'url' => ['view', 'barang_produksi_id' => $model->barang_produksi_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="card table-card p-4">
    <h1><?= Html::encode($this->title) ?></h1>

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
                    <th>Nama Barang</th>
                    <th>Nama Jenis</th>
                    <th>Ukuran</th>
                    <th style="width:100px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="product-rows">
                <tr data-product-index="0">
                    <td class="text-center">1</td>
                    <td>
                        <input type="text" name="Barangproduksi[kode_barang_produksi]" class="form-control form-control-sm" value="<?= Html::encode($model->kode_barang_produksi) ?>" placeholder="PTR-12">
                    </td>
                    <td>
                        <input type="text" name="Barangproduksi[nama]" class="form-control form-control-sm" value="<?= Html::encode($model->nama) ?>" placeholder="Kaos Kaki coklat Cap budi">
                    </td>
                    <td>
                        <input type="text" name="Barangproduksi[nama_jenis]" class="form-control form-control-sm" value="<?= Html::encode($model->nama_jenis) ?>" placeholder="Nama Jenis">
                    </td>
                    <td>
                        <input type="text" name="Barangproduksi[ukuran]" class="form-control form-control-sm" value="<?= Html::encode($model->ukuran) ?>" placeholder="Ukuran">
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#bom-0">
                            BOM
                        </button>
                    </td>
                </tr>
                <tr class="collapse show" id="bom-0" data-product-index="0">
                    <td colspan="6">
                        <div class="p-2 border rounded bg-light">
                            <h6 class="mb-2">Detail BOM</h6>
                            <table class="table table-sm table-bordered mb-0" id="bom-table-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">No</th>
                                        <th>Bahan</th>
                                        <th style="width:150px;">Kuantitas</th>
                                        <th style="width:200px;">Catatan</th>
                                        <th style="width:80px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="bom-rows">
                                    <!-- BOM akan ditambahkan via JS -->
                                </tbody>
                            </table>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-success" onclick="addBomRow()">
                                    + Tambah Bahan
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update</button>
            <?= Html::a('Back', ['view', 'barang_produksi_id' => $model->barang_produksi_id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </form>
</div>

<?php
// Data untuk JS
$barangListJson = json_encode(array_map(function($item) {
    return ['nama' => $item['nama']];
}, $barangList));

// Existing BOM data
$existingBomJson = json_encode(array_map(function($bom) {
    return [
        'barang_id' => $bom->barang_id,
        'qty' => $bom->qty_BOM,
        'catatan' => $bom->catatan,
    ];
}, $existingBom));

$this->registerJs("
const barangList = $barangListJson;
const barangIds = " . json_encode(array_keys($barangList)) . ";
const existingBom = $existingBomJson;
let bomCounter = 0;

// Tambah row BOM baru
function addBomRow(barangId = '', qty = '', catatan = '') {
    const tbody = document.getElementById('bom-rows');
    const newIndex = bomCounter;
    
    let barangOptions = '<option value=\"\">Pilih Bahan</option>';
    barangIds.forEach(function(id) {
        const selected = (id == barangId) ? 'selected' : '';
        barangOptions += '<option value=\"' + id + '\" ' + selected + '>' + barangList[id].nama + '</option>';
    });
    
    const newRow = `
        <tr>
            <td class=\"text-center\">` + (newIndex + 1) + `</td>
            <td>
                <select name=\"bom[` + newIndex + `][barang_id]\" class=\"form-select form-select-sm\" required>
                    ` + barangOptions + `
                </select>
            </td>
            <td>
                <input type=\"number\" name=\"bom[` + newIndex + `][qty]\" class=\"form-control form-control-sm\" step=\"0.01\" min=\"0\" value=\"` + qty + `\" placeholder=\"1\" required>
            </td>
            <td>
                <input type=\"text\" name=\"bom[` + newIndex + `][catatan]\" class=\"form-control form-control-sm\" value=\"` + catatan + `\" placeholder=\"Catatan\">
            </td>
            <td class=\"text-center\">
                <button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"removeBomRow(this)\">-</button>
            </td>
        </tr>
    `;
    
    tbody.insertAdjacentHTML('beforeend', newRow);
    bomCounter++;
    updateBomNumbers();
}

// Hapus row BOM
function removeBomRow(btn) {
    const rows = document.getElementById('bom-rows').querySelectorAll('tr');
    if (rows.length <= 1) {
        alert('Minimal harus ada 1 bahan!');
        return;
    }
    
    if (confirm('Hapus bahan ini?')) {
        btn.closest('tr').remove();
        updateBomNumbers();
    }
}

// Update nomor urut
function updateBomNumbers() {
    const rows = document.querySelectorAll('#bom-rows > tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

// Load existing BOM saat halaman load
$(document).ready(function() {
    if (existingBom.length > 0) {
        existingBom.forEach(function(bom) {
            addBomRow(bom.barang_id, bom.qty, bom.catatan);
        });
    } else {
        addBomRow();
    }
});
", \yii\web\View::POS_END);
?>