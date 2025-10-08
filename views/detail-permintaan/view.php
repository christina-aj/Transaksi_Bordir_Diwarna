<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DetailPermintaan $model */

$this->title = $model->detail_permintaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Detail Permintaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="detail-permintaan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'detail_permintaan_id' => $model->detail_permintaan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'detail_permintaan_id' => $model->detail_permintaan_id], [
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
            'detail_permintaan_id',
            'permintaan_penjualan_id',
            'barang_produksi_id',
            'qty_permintaan',
            'catatan',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
