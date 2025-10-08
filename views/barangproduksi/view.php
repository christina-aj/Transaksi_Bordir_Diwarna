<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksi $model */

$this->title = $model->barang_produksi_id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Produksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'barang_produksi_id',
            'kode_barang_produksi',
            'nama',
            'nama_jenis',
            'ukuran',
            'deskripsi:ntext',
        ],
    ]) ?>
    <p>
        <?= Html::a('Update', ['update', 'barang_produksi_id' => $model->barang_produksi_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'barang_produksi_id' => $model->barang_produksi_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['barangproduksi/index'], ['class' => 'btn btn-secondary']) ?>
    </p>
</div>
