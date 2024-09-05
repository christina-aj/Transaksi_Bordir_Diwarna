<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\LaporanProduksi $model */

$this->title = $model->laporan_id;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Produksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'laporan_id' => $model->laporan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'laporan_id' => $model->laporan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['laporan-produksi/index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'laporan_id',
            'mesin_id',
            'shift_id',
            'tanggal_kerja:date',
            'nama_kerjaan',
            'vs',
            'stitch',
            'kuantitas',
            'bs',
        ],
    ]) ?>

</div>
