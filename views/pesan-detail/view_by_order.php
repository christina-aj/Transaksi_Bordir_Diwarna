<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PesanDetail $model */

$this->title = 'Detail Pemesanan Kode: ' . $pemesanan_id;
$this->params['breadcrumbs'][] = ['label' => 'Pesan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php foreach ($models as $model): ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => 'Kode Pemesanan',
                    'attribute' => 'pemesanan_id',
                    'value' => function ($model) {
                        return $model->getFormattedOrderId(); // Assuming you have a method to format ID
                    },
                ],
                'barang_id' => [
                    'label' => 'Nama Barang',
                    'attribute' => 'barang_id',
                    'value' => function ($model) {
                        $barang = $model->barang;
                        $unit = $barang->unit;
                        return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                    }
                ],
                'qty' => [
                    'label' => 'Quantity Pesan',
                    'attribute' => 'qty',
                ],
                'qty_terima' => [
                    'label' => 'Quantity Terima',
                    'attribute' => 'qty_terima',
                ],
                'catatan',
                'langsung_pakai' => [
                    'label' => 'Langsung Pakai',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->langsung_pakai == 1
                            ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                            : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                    },
                ],
                'is_correct' => [
                    'label' => 'Barang Sesuai',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->is_correct == 1
                            ? Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;'])
                            : Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']);
                    },
                ],
                'created_at:datetime',
                'update_at:datetime',
            ],
        ]) ?>
        <hr>
    <?php endforeach; ?>
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