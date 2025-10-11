<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualan $model */

$this->title = $model->riwayat_penjualan_id;
$this->params['breadcrumbs'][] = ['label' => 'Riwayat Penjualans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="riwayat-penjualan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'riwayat_penjualan_id' => $model->riwayat_penjualan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'riwayat_penjualan_id' => $model->riwayat_penjualan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'riwayat_penjualan_id',
            'barang_produksi_id',
            'qty_penjualan',
            'bulan_periode',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
