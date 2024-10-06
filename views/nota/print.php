<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var app\models\Nota $model */

$this->title = 'Nota ' . $model->nota_id;
?>
<div class="nota-print" style="width: 800px; margin: 0 auto;">

    <div style="text-align: center; margin-bottom: 20px;">
        <div style="position: relative;">
            <h1 style="text-align: center; margin-bottom: 0;">CV.DIGITAL WARNA MANDIRI</h1>
            <img src="/assets/images/diwarna-logo-png.png" alt="Logo" style="position: absolute; top: 0; right: 0; width: 100px; height: auto;">
        </div>
        <p style="margin: 0;">Jl. Kedinding Lor Gg Anggrek No. 36, Surabaya<br>
        Tel: +62 82117761248</p>
    </div>

    <h2 style="text-align: center; text-decoration: underline;">FAKTUR PENJUALAN</h2>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td><strong>Nama Konsumen:</strong> <?= Html::encode($model->nama_konsumen) ?></td>
            <td><strong>Tanggal:</strong> <?= Html::encode($model->tanggal) ?></td>
        </tr>
        <tr>
            <td><strong>Total Qty:</strong> <?= Html::encode($model->total_qty) ?></td>
            <td><strong>Total Harga:</strong> Rp. <?= Html::encode($model->total_harga) ?></td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">No</th>
                <th style="border: 1px solid #000; padding: 8px;">Keterangan</th>
                <th style="border: 1px solid #000; padding: 8px;">Jumlah</th>
                <th style="border: 1px solid #000; padding: 8px;">Harga</th>
                <th style="border: 1px solid #000; padding: 8px;">Jumlah Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->barangList as $index => $barang): ?>
                <tr>
                    <td style="border: 1px solid #000; padding: 8px;"><?= Html::encode($index + 1) ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?= Html::encode($barang) ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?= Html::encode($model->qtyList[$index]) ?></td>
                    <td style="border: 1px solid #000; padding: 8px;">Rp. <?= Html::encode($model->hargaList[$index]) ?></td>
                    <td style="border: 1px solid #000; padding: 8px;">Rp. <?= Html::encode($model->hargaList[$index] * $model->qtyList[$index]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: right; margin-bottom: 20px;">
        <p><strong>Total Harga:</strong> Rp. <?= Html::encode($model->total_harga) ?></p>
    </div>

    <div style="text-align: right; margin-bottom: 20px;">
        <p><strong>Owner</strong></p>
        <br><br>
        <p>(Nama)</p>
    </div>

    <div style="text-align: center; margin-top: 30px;">
  
        <a href="<?= Url::to(['nota/view', 'nota_id' => $model->nota_id]) ?>" class="btn-back" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; text-decoration: none; margin-right: 10px;">Back</a>
        
        <!-- Print Button -->
        <button onclick="window.print()" style="background-color: #007BFF; color: white; padding: 10px 20px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;">Print Nota</button>
    </div>

</div>

<style>
    /* Hide elements when printing */
    @media print {
        .sidebar, /* Add your sidebar class here */
        .btn-back,
        button, 
        .logo, /* Hides logo when printing */
        header, /* Hides any header containing localhost details */
        footer /* Hides footer if it contains localhost */ {
            display: none !important;
        }
        
        body {
            font-family: Arial, sans-serif;
        }
        .nota-print {
            margin: 20px;
        }
    }

    /* Styling for buttons */
    button:hover, .btn-back:hover {
        opacity: 0.8;
    }
</style>