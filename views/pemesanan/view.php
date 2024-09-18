<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */

$this->title = $model->pemesanan_id;
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
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
            'pemesanan_id',
            // 'barang_id',
            // 'user_id',
            'barang.nama_barang' => [
                'attribute' => 'barang_id', // Bisa menggunakan attribute lain sesuai kebutuhan
                'label' => 'Detail Barang',
                'value' => function ($model) {
                    $barang = $model->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                }
            ],
            // 'user_id',
            'user.nama_pengguna',
            'tanggal',
            'qty',
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'pemesanan_id' => $model->pemesanan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['pemesanan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>