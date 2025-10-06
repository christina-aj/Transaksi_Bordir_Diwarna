<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanDetail $model */

$this->title = $model->gunadetail_id;
$this->params['breadcrumbs'][] = ['label' => 'Penggunaan Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="penggunaan-detail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'gunadetail_id' => $model->gunadetail_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'gunadetail_id' => $model->gunadetail_id], [
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
            'gunadetail_id',
            'penggunaan_id',
            'barang_id',
            'jumlah_digunakan',
            'catatan',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
