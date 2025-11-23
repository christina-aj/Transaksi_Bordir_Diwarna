<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $stockRopList array */

$this->title = 'Daily ROP Alert';
?>

<style>
    .alert-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin: 20px 0;
        border-radius: 4px;
    }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .items-table th {
        background: #dc3545;
        color: white;
        padding: 10px;
        text-align: left;
        font-size: 12px;
    }
    .items-table td {
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        font-size: 13px;
    }
    .danger {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<div class="alert-box">
    <strong>⚠️ DAILY STOCK ALERT</strong><br>
    Terdapat <strong><?= count($stockRopList) ?> barang</strong> yang stock-nya sudah dibawah ROP dan perlu segera dipesan.
</div>

<h3>📋 Daftar Barang:</h3>

<table class="items-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Stock</th>
            <th>ROP</th>
            <th>Kekurangan</th>
            <th>Rekomendasi Order (EOQ)</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stockRopList as $index => $data): ?>
            <?php 
                $barang = $data['barang'];
                $stockRop = $data['stockRop'];
                $currentStock = $data['currentStock'];
                $deficit = $stockRop->jumlah_rop - $currentStock;
            ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= Html::encode($barang->kode_barang) ?></td>
                <td><?= Html::encode($barang->nama_barang) ?></td>
                <td class="danger"><?= number_format($currentStock, 0) ?></td>
                <td><?= number_format($stockRop->jumlah_rop, 0) ?></td>
                <td><?= number_format($deficit, 0) ?></td>
                <td><strong><?= number_format($stockRop->jumlah_eoq, 0) ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p style="text-align: center; margin: 30px 0;">
    <a href="<?= Url::to(['stock-rop/index'], true) ?>" 
       style="display: inline-block; padding: 12px 30px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;">
        📊 Lihat Laporan Lengkap
    </a>
</p>