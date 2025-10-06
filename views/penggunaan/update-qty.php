<?php
// views/penggunaan/update-qty.php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $modelPenggunaan */
/** @var app\models\PenggunaanDetail[] $modelDetails */
/** @var array $stockPerArea */

$this->title = 'Update Quantity - ' . $modelPenggunaan->getFormattedGunaId();
?>

<div class="penggunaan-update-qty">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        
        <div class="row mx-3">
            <div class="col-md-4">
                <p><strong>Kode Penggunaan:</strong> <?= $modelPenggunaan->getFormattedGunaId() ?></p>
                <p><strong>Nama Pengguna:</strong> <?= $modelPenggunaan->user->nama_pengguna ?? '-' ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($modelPenggunaan->tanggal) ?></p>
                <p><strong>Total Item:</strong> <?= $modelPenggunaan->total_item_penggunaan ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $modelPenggunaan->getStatusLabel() ?></p>
            </div>
        </div>
        <hr>

        <div class="card-body mx-4">
            <?php $form = ActiveForm::begin([
                'id' => 'penggunaan-qty-form',
                'action' => ['update-qty', 'penggunaan_id' => $modelPenggunaan->penggunaan_id],
                'method' => 'post',
                'options' => [
                    'onsubmit' => 'console.log("Form submitting with data:", new FormData(this)); return true;'
                ]
            ]); ?>
            
            <div id="penggunaan-details">
                <?php foreach ($modelDetails as $index => $detail): ?>
                    <div class="detail-item mb-4" data-detail-id="<?= $detail->gunadetail_id ?>" data-index="<?= $index ?>">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong><?= $detail->barang->kode_barang ?> - <?= $detail->barang->nama_barang ?></strong>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-info">Diminta: <?= $detail->jumlah_digunakan ?></span>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="stock-info">
                                            <strong>Stock Available:</strong>
                                            <?php 
                                            $barangStock = $stockPerArea[$detail->barang_id] ?? [];
                                            $totalStock = array_sum(array_column($barangStock, 'quantity_akhir'));
                                            ?>
                                            <span class="badge <?= $totalStock >= $detail->jumlah_digunakan ? 'bg-success' : 'bg-danger' ?>">
                                                Total: <?= $totalStock ?>
                                            </span>
                                            
                                            <?php foreach ($barangStock as $area => $stock): ?>
                                                <span class="badge bg-secondary ms-1">
                                                    Area <?= $area ?>: <?= $stock['quantity_akhir'] ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <div class="area-selections" id="area-selections-<?= $index ?>">
                                    <!-- Area selection rows akan ditambah di sini via JavaScript -->
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-success btn-sm add-area-btn" 
                                                data-index="<?= $index ?>" data-barang-id="<?= $detail->barang_id ?>">
                                            <i class="fas fa-plus"></i> Tambah Area
                                        </button>
                                        <span class="ms-3">
                                            <strong>Total Dipilih: </strong>
                                            <span class="total-selected" data-index="<?= $index ?>">0</span> / 
                                            <span class="total-required"><?= $detail->jumlah_digunakan ?></span>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <?= Html::textArea("details[$index][catatan]", $detail->catatan, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Catatan...',
                                            'rows' => 2
                                        ]) ?>
                                    </div>
                                </div>
                                
                                <!-- Hidden inputs -->
                                <?= Html::hiddenInput("details[$index][gunadetail_id]", $detail->gunadetail_id) ?>
                                <?= Html::hiddenInput("details[$index][barang_id]", $detail->barang_id) ?>
                                <?= Html::hiddenInput("details[$index][jumlah_digunakan]", $detail->jumlah_digunakan) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-group mt-4">
                <?= Html::submitButton('Update & Complete', [
                    'class' => 'btn btn-primary',
                    'id' => 'submit-btn',
                    'disabled' => true
                ]) ?>
                <?= Html::a('Back', ['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id], [
                    'class' => 'btn btn-secondary'
                ]) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let areaRowCounter = 0;
    
    // Data stock per area dari PHP
    const stockData = <?= json_encode($stockPerArea) ?>;
    
    // Area names mapping
    const areaNames = {
        1: 'Area 1',
        2: 'Area 2', 
        3: 'Area 3',
        4: 'Area 4'
    };
    
    // Add area button click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-area-btn') || e.target.closest('.add-area-btn')) {
            const btn = e.target.closest('.add-area-btn');
            const index = btn.dataset.index;
            const barangId = btn.dataset.barangId;
            addAreaRow(index, barangId);
        }
    });
    
    // Remove area button click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-area-btn') || e.target.closest('.remove-area-btn')) {
            const btn = e.target.closest('.remove-area-btn');
            const row = btn.closest('.area-row');
            row.remove();
            updateTotalSelected();
            validateForm();
        }
    });
    
    // Quantity change
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('area-quantity')) {
            updateTotalSelected();
            validateMaxQuantity(e.target);
            validateForm();
        }
    });
    
    // Area change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('area-select')) {
            updateMaxQuantity(e.target);
            validateForm();
        }
    });
    
    function addAreaRow(detailIndex, barangId) {
        const container = document.getElementById('area-selections-' + detailIndex);
        const availableAreas = getAvailableAreas(barangId, detailIndex);
        
        if (availableAreas.length === 0) {
            alert('Semua area sudah dipilih atau tidak ada stock tersedia');
            return;
        }
        
        const rowHtml = `
            <div class="area-row row mb-2" data-row-id="${areaRowCounter}">
                <div class="col-md-4">
                    <select name="details[${detailIndex}][areas][${areaRowCounter}][area_gudang]" 
                            class="form-control area-select" required>
                        <option value="">Pilih Area</option>
                        ${availableAreas.map(area => 
                            `<option value="${area.id}" data-max="${area.stock}">
                                ${area.name} (Stock: ${area.stock})
                            </option>`
                        ).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" 
                           name="details[${detailIndex}][areas][${areaRowCounter}][quantity]"
                           class="form-control area-quantity" 
                           placeholder="Jumlah" min="1" max="0" required>
                </div>
                <div class="col-md-3">
                    <span class="form-control-plaintext">
                        Max: <span class="max-quantity">0</span>
                    </span>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm remove-area-btn">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', rowHtml);
        areaRowCounter++;
    }
    
    function getAvailableAreas(barangId, detailIndex) {
        const usedAreas = [];
        const container = document.getElementById('area-selections-' + detailIndex);
        const selects = container.querySelectorAll('.area-select');
        
        selects.forEach(select => {
            if (select.value) usedAreas.push(select.value);
        });
        
        const barangStock = stockData[barangId] || {};
        const available = [];
        
        for (const areaId in barangStock) {
            if (!usedAreas.includes(areaId) && barangStock[areaId].quantity_akhir > 0) {
                available.push({
                    id: areaId,
                    name: areaNames[areaId] || 'Area ' + areaId,
                    stock: barangStock[areaId].quantity_akhir
                });
            }
        }
        
        return available;
    }
    
    function updateMaxQuantity(selectElement) {
        const row = selectElement.closest('.area-row');
        const quantityInput = row.querySelector('.area-quantity');
        const maxSpan = row.querySelector('.max-quantity');
        const selectedOption = selectElement.selectedOptions[0];
        
        if (selectedOption) {
            const maxStock = selectedOption.dataset.max || 0;
            quantityInput.max = maxStock;
            maxSpan.textContent = maxStock;
            
            // Reset quantity if it exceeds new max
            if (quantityInput.value > maxStock) {
                quantityInput.value = maxStock;
            }
        }
    }
    
    function validateMaxQuantity(quantityInput) {
        const max = parseInt(quantityInput.max);
        const value = parseInt(quantityInput.value);
        
        if (value > max) {
            quantityInput.value = max;
            alert('Jumlah tidak boleh melebihi stock yang tersedia: ' + max);
        }
    }
    
    function updateTotalSelected() {
        document.querySelectorAll('[data-index]').forEach(function(element) {
            if (element.classList.contains('total-selected')) {
                const index = element.dataset.index;
                const container = document.getElementById('area-selections-' + index);
                const quantityInputs = container.querySelectorAll('.area-quantity');
                
                let total = 0;
                quantityInputs.forEach(input => {
                    total += parseInt(input.value) || 0;
                });
                
                element.textContent = total;
            }
        });
    }
    
    function validateForm() {
        let isValid = true;
        const detailItems = document.querySelectorAll('.detail-item');
        
        detailItems.forEach(item => {
            const index = item.dataset.index;
            const requiredQty = parseInt(item.querySelector('.total-required').textContent);
            const selectedQty = parseInt(item.querySelector('.total-selected').textContent);
            
            if (selectedQty !== requiredQty) {
                isValid = false;
            }
        });
        
        document.getElementById('submit-btn').disabled = !isValid;
    }
    
    // Initialize first area for each detail
    <?php foreach ($modelDetails as $index => $detail): ?>
        addAreaRow(<?= $index ?>, <?= $detail->barang_id ?>);
    <?php endforeach; ?>
});
</script>

<style>
.stock-info .badge {
    font-size: 0.8em;
}

.area-row {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.detail-item {
    border-left: 4px solid #007bff;
}

.total-selected {
    font-weight: bold;
    color: #007bff;
}
</style>