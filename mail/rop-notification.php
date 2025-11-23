<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $barang app\models\Barang */
/* @var $stockRop app\models\StockRop */
/* @var $currentStock float */
/* @var $deficit float */
/* @var $recommendedOrder float */

$this->title = 'Peringatan Stock ROP';
?>

<style>
    .alert-box {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin: 20px 0;
        border-radius: 4px;
    }
    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: #f8f9fa;
    }
    .info-table th {
        background: #007bff;
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: normal;
    }
    .info-table td {
        padding: 12px;
        border-bottom: 1px solid #dee2e6;
    }
    .info-table tr:last-child td {
        border-bottom: none;
    }
    .danger {
        color: #dc3545;
        font-weight: bold;
        font-size: 16px;
    }
    .success {
        color: #28a745;
        font-weight: bold;
        font-size: 16px;
    }
    .recommendation-box {
        background: #d1ecf1;
        border-left: 4px solid #17a2b8;
        padding: 15px;
        margin: 20px 0;
        border-radius: 4px;
    }
    .btn {
        display: inline-block;
        padding: 12px 30px;
        background: #007bff;
        color: white !important;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        margin: 20px 0;
    }
    .btn:hover {
        background: #0056b3;
    }
    .text-center {
        text-align: center;
    }
</style>

<div class="alert-box">
    <strong>!! PERHATIAN !!</strong><br>
    Stock barang <strong><?= Html::encode($barang->nama_barang) ?></strong> telah mencapai atau dibawah titik pemesanan ulang (ROP). 
    <strong>Segera lakukan pemesanan</strong> untuk menghindari kehabisan stock!
</div>

<h3 style="color: #333; margin-top: 30px;">DETAIL BARANG:</h3>

<table class="info-table">
    <tr>
        <th colspan="2">Informasi Barang</th>
    </tr>
    <tr>
        <td width="40%"><strong>Kode Barang</strong></td>
        <td><?= Html::encode($barang->kode_barang) ?></td>
    </tr>
    <tr>
        <td><strong>Nama Barang</strong></td>
        <td><?= Html::encode($barang->nama_barang) ?></td>
    </tr>
</table>

<table class="info-table">
    <tr>
        <th colspan="2">Status Stok</th>
    </tr>
    <tr>
        <td width="40%"><strong>Stock Saat Ini</strong></td>
        <td class="danger">
            <?= number_format($currentStock, 0, ',', '.') ?> <?= Html::encode($barang->unit->satuan ?? 'unit') ?>
            <br><small style="color: #6c757d;">DIBAWAH BATAS AMAN</small>
        </td>
    </tr>
    <tr>
        <td><strong>Reorder Point (ROP)</strong></td>
        <td><?= number_format($stockRop->jumlah_rop, 0, ',', '.') ?> <?= Html::encode($barang->unit->satuan ?? 'unit') ?></td>
    </tr>
    <tr>
        <td><strong>Safety Stock</strong></td>
        <td><?= number_format($stockRop->safety_stock, 0, ',', '.') ?> <?= Html::encode($barang->unit->satuan ?? 'unit') ?></td>
    </tr>
    <!-- <tr>
        <td><strong>Kekurangan Stock</strong></td>
        <td class="danger"><?= number_format(max(0, $deficit), 0, ',', '.') ?> <?= Html::encode($barang->unit->satuan ?? 'unit') ?></td>
    </tr> -->
    <tr>
        <td><strong>Periode Forecast</strong></td>
        <td><?= $stockRop->getPeriodeFormatted() ?></td>
    </tr>
    <tr>
        <td><strong>===============</strong></td>
    </tr>
</table>

<div class="recommendation-box">
    <strong>REKOMENDASI JUMLAH ORDER:</strong><br>
    Berdasarkan perhitungan Economic Order Quantity (EOQ), disarankan untuk memesan:<br>
    <div class="success" style="font-size: 20px; margin: 10px 0;">
        <?= number_format($recommendedOrder, 0, ',', '.') ?> <?= Html::encode($barang->unit->satuan ?? 'unit') ?>
    </div>
    <small style="color: #0c5460;">
        Jumlah ini optimal untuk meminimalkan biaya pemesanan dan penyimpanan.
    </small>
</div>

<div class="text-center">
    <a href="<?= Url::to(['pemesanan/create', 'stock_rop_id' => $stockRop->stock_rop_id], true) ?>" class="btn">
        -> Buat Pemesanan Sekarang (klik)
    </a>
</div>

<hr style="margin: 30px 0; border: none; border-top: 1px solid #dee2e6;">

<p style="color: #6c757d; font-size: 12px; text-align: center;">
    <strong>Tips:</strong> Pastikan untuk melakukan pemesanan segera agar tidak terjadi stock out yang dapat mengganggu operasional.
</p>