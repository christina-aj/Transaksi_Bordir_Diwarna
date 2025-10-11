<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\DataPerhitungan $model */

$this->title = $model->data_perhitungan_id;
$this->params['breadcrumbs'][] = ['label' => 'Data Perhitungans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="data-perhitungan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'data_perhitungan_id' => $model->data_perhitungan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'data_perhitungan_id' => $model->data_perhitungan_id], [
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
            'data_perhitungan_id',
            'barang_id',
            'biaya_pesan',
            'biaya_simpan',
            'safety_stock',
            'lead_time_rerata:datetime',
            'periode_mulasi',
            'periode_selesai',
            'created_at',
        ],
    ]) ?>

</div>
