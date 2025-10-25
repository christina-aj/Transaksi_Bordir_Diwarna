<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\MasterPelanggan $model */
/** @var app\models\BarangCustomPelanggan[] $barangCustom */

$this->title = 'Data Pelanggan : ' . $model->nama_pelanggan;
$this->params['breadcrumbs'][] = ['label' => 'Master Pelanggans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">
    <div class="card table-card p-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div class="d-flex mb-4">
            <p class="mt-2 me-4 mb-0">
                <strong>Kode Pelanggan:</strong> <?= Html::encode($model->kode_pelanggan) ?>
            </p>
            <p class="mt-2 mb-0">
                <strong>Nama Pelanggan:</strong> <?= Html::encode($model->nama_pelanggan) ?>
            </p>
        </div>
        <h4>Data Produk</h4>
        <?php if (!empty($barangCustom)): ?>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th style="width:100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($barangCustom as $i => $barang): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= Html::encode($barang->kode_barang_custom) ?></td>
                            <td><?= Html::encode($barang->nama_barang_custom) ?></td>
                            <td>
                                <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#detail-<?= $i ?>" aria-expanded="false">
                                    Detail
                                </button>
                            </td>
                        </tr>
                        <tr class="collapse" id="detail-<?= $i ?>">
                            <td colspan="4">
                                <div class="p-2 border rounded bg-light">
                                    <h6 class="mb-2">Detail BOM</h6>
                                    <?php if (!empty($barang->bomCustoms)): ?>
                                        <table class="table table-sm mb-0">
                                            <thead class="table table-bordered">
                                                <tr>
                                                    <th style="width:40px;">No</th>
                                                    <th>Nama Bahan</th>
                                                    <th>Qty per Unit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($barang->bomCustoms as $j => $bom): ?>
                                                    <tr>
                                                        <td><?= $j + 1 ?></td>
                                                        <td><?= Html::encode($bom->barang->nama_barang ?? '-') ?></td>
                                                        <td><?= Html::encode($bom->qty_per_unit) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    <?php else: ?>
                                        <em>Belum ada detail bahan untuk produk ini.</em>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><em>Belum ada produk custom untuk pelanggan ini.</em></p>
        <?php endif; ?>

        <div class="mt-3">
            <?= Html::a('Create Produk', ['barang-custom-pelanggan/create', 'pelanggan_id' => $model->pelanggan_id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('Update Semua Produk', ['barang-custom-pelanggan/update', 'pelanggan_id' => $model->pelanggan_id], ['class' => 'btn btn-warning']) ?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>
