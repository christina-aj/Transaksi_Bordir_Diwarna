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

    <p>
        <?= Html::a('Update', ['update', 'belidetail_id' => $model->belidetail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'belidetail_id' => $model->belidetail_id], [
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
            'belidetail_id',
            'pembelian_id',
            'pesandetail_id',
            'cek_barang',
            'total_biaya',
            'catatan',
            'is_correct',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>