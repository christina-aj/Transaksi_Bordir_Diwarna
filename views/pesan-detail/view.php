<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PesanDetail $model */

$this->title = $model->pesandetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Pesan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pesandetail_id' =>
            [
                'label' => 'Kode Pemesanan',
                'attribute' => 'pemesanan_id',
                'value' => function ($model) {
                    return $model->getFormattedOrderId(); // Call the method to get the formatted ID
                },
            ],
            // 'pemesanan_id',
            'barang_id' => [
                'label' => 'Nama barang',
                'attribute' => 'barang_id',
                'value' => function ($model) {
                    $barang = $model->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                }
            ],
            'qty' => [
                'label' => 'Quantity pesan',
                'attribute' => 'qty',
            ],
            'qty_terima',
            'catatan',
            'langsung_pakai' => [
                'label' => 'langsung Pakai',
                'attribute' => 'langsung_pakai',
                'format' => 'raw', // This allows for raw HTML output (for icons)
                'value' => function ($model) {
                    // Check the value of the status field
                    if ($model->langsung_pakai == 1) {
                        // Active status (1)
                        return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                    } else {
                        // Inactive status (0)
                        return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                    }
                },
            ],
            'is_correct' => [
                'label' => 'Barang Lengkap',
                'attribute' => 'is_correct',
                'filter' => false,
                'format' => 'raw', // This allows for raw HTML output (for icons)
                'value' => function ($model) {
                    // Check the value of the status field
                    if ($model->is_correct == 1) {
                        // Active status (1)
                        return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                    } else {
                        // Inactive status (0)
                        return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                    }
                },
            ],
            'created_at:datetime',
            'update_at:datetime',
        ],
    ]) ?>
    <div class="form-group">

        <?= Html::a('Update', ['update', 'pesandetail_id' => $model->pesandetail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'pesandetail_id' => $model->pesandetail_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['pesan-detail/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

</div>