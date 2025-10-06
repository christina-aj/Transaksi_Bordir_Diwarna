<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Gudang $model */

$this->title = $model->id_gudang;
$this->params['breadcrumbs'][] = ['label' => 'Gudangs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_gudang',
            'tanggal',
            [
                'attribute' => 'barang.nama_barang',
                'label' => 'Nama Barang'
            ],
            'user_id',
            [
                'attribute' => 'area_gudang',
                'value' => $model->getAreaLabel(),
                'label' => 'Area Gudang'
            ],
            'quantity_awal',
            'quantity_masuk',
            'quantity_keluar',
            [
                'attribute' => 'quantity_akhir',
                'value' => $model->quantity_akhir,
                'contentOptions' => [
                    'class' => $model->quantity_akhir <= 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold'
                ]
            ],
            'catatan',
            'created_at:datetime',
            'update_at:datetime',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'id_gudang' => $model->id_gudang], ['class' => 'btn btn-primary']) ?>
        
        <?php if ($model->quantity_akhir > 0): ?>
            <?= Html::a('Move Area', ['move-area', 'barang_id' => $model->barang_id, 'area_asal' => $model->area_gudang], ['class' => 'btn btn-warning']) ?>
        <?php endif; ?>
        
        <?= Html::a('Delete', ['delete', 'id_gudang' => $model->id_gudang], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['gudang/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>

<style>
.text-danger {
    color: #dc3545 !important;
}
.text-success {
    color: #28a745 !important;
}
.font-weight-bold {
    font-weight: bold !important;
}
</style>