<?php

use yii\helpers\Html;

/** @var app\models\Nota $model */

$this->title = 'Nota ' . $model->nota_id;
?>
<div class="nota-print">

    <h1 style="text-align: center;"><?= Html::encode($this->title) ?></h1>

    <p><strong>ID Nota:</strong> <?= Html::encode($model->nota_id) ?></p>
    <p><strong>Nama Konsumen:</strong> <?= Html::encode($model->nama_konsumen) ?></p>
    <p><strong>Tanggal:</strong> <?= Html::encode($model->tanggal) ?></p>
    <p><strong>Total Qty:</strong> <?= Html::encode($model->total_qty) ?></p>
    <p><strong>Total Harga:</strong> <?= Html::encode($model->total_harga) ?></p>

    <h2 style="text-align: center;">Items</h2>

    <table class="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th style="border: 1px solid #000;">Barang</th>
                <th style="border: 1px solid #000;">Harga</th>
                <th style="border: 1px solid #000;">Qty</th>
                <th style="border: 1px solid #000;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->barangList as $index => $barang): ?>
                <tr>
                    <td style="border: 1px solid #000;"><?= Html::encode($barang) ?></td>
                    <td style="border: 1px solid #000;"><?= Html::encode($model->hargaList[$index]) ?></td>
                    <td style="border: 1px solid #000;"><?= Html::encode($model->qtyList[$index]) ?></td>
                    <td style="border: 1px solid #000;"><?= Html::encode($model->hargaList[$index] * $model->qtyList[$index]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <button onclick="window.print()" style="margin-top: 20px;">Print this page</button>

</div>

<style>
    @media print {
        body {
            font-family: Arial, sans-serif;
        }
        .nota-print {
            margin: 20px;
        }
        button {
            display: none; /* Hide the button when printing */
        }
    }
</style>

