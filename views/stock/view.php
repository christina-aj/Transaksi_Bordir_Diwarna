<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\date\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Stock $model */

$this->title = $model->stock_id;
$this->params['breadcrumbs'][] = ['label' => 'Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'stock_id',
            [
                'attribute' => 'tambah_stock',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format menjadi dd-mm-yyyy
                'label' => 'Tanggal',
            ],
            // 'tambah_stock',
            'barang_id',
            'barang.kode_barang',
            'barang.nama_barang',
            'quantity_awal',
            'quantity_masuk',
            'quantity_keluar',
            'quantity_akhir',
            'user_id',
            'user.nama_pengguna',
            'is_ready',
            'is_new',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div class="mb-5">
        <?= Html::a('Update', ['update', 'stock_id' => $model->stock_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'stock_id' => $model->stock_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['stock/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>