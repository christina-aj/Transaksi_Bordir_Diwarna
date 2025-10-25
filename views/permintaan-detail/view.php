<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PermintaanDetail $model */

$this->title = $model->permintaan_detail_id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="permintaan-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'permintaan_detail_id' => $model->permintaan_detail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'permintaan_detail_id' => $model->permintaan_detail_id], [
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
            'permintaan_detail_id',
            'permintaan_id',
            'barang_produksi_id',
            'barang_custom_pelanggan_id',
            'qty_permintaan',
            'catatan',
        ],
    ]) ?>

</div>
