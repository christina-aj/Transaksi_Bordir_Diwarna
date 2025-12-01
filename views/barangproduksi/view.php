<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = 'Data Produk Jadi : ' . $model->nama;
$this->params['breadcrumbs'][] = ['label' => 'Barang Produksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">
    <div class="card table-card p-4">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <div class="d-flex mb-4 flex-wrap">
            <p class="mt-2 me-4 mb-2">
                <strong>Kode Barang:</strong> <?= Html::encode($model->kode_barang_produksi) ?>
            </p>
            <p class="mt-2 me-4 mb-2">
                <strong>Nama:</strong> <?= Html::encode($model->nama) ?>
            </p>
            <p class="mt-2 me-4 mb-2">
                <strong>Jenis:</strong> <?= Html::encode($model->nama_jenis) ?>
            </p>
            <p class="mt-2 me-4 mb-2">
                <strong>Ukuran:</strong> <?= Html::encode($model->ukuran) ?>
            </p>
        </div>

        <?php if ($model->deskripsi): ?>
            <div class="mb-4">
                <strong>Deskripsi:</strong>
                <p><?= Html::encode($model->deskripsi) ?></p>
            </div>
        <?php endif; ?>

        <h4>Detail BOM (Bill of Materials)</h4>
        <?php if (!empty($model->bomDetails)): ?>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px;">No</th>
                        <th>Kode Bahan</th>
                        <th>Nama Bahan</th>
                        <th style="width:150px;">Qty per Unit</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model->bomDetails as $i => $bom): ?>
                        <tr>
                            <td class="text-center"><?= $i + 1 ?></td>
                            <td><?= Html::encode($bom->barang->kode_barang ?? '-') ?></td>
                            <td><?= Html::encode($bom->barang->nama_barang ?? '-') ?></td>
                            <td class="text-end"><?= Html::encode($bom->qty_BOM) ?></td>
                            <td><?= Html::encode($bom->catatan ?: '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                <em>Belum ada detail BOM untuk produk ini.</em>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <?= Html::a('Update', ['update', 'barang_produksi_id' => $model->barang_produksi_id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'barang_produksi_id' => $model->barang_produksi_id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
</div>