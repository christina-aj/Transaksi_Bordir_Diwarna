<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PembelianDetail $model */

$this->title = $model->belidetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Pembelian Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pembelian.tanggal',
            'belidetail_id',
            'pembelian_id',
            'pembelian.kode_struk',
            'barang_id',
            'barang.kode_barang',
            'barang.nama_barang',
            'harga_barang',
            'quantity_barang',
            'total_biaya',
            'catatan',
            'langsung_pakai',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'belidetail_id' => $model->belidetail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'belidetail_id' => $model->belidetail_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['pembelian-detail/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>