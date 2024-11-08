<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\LaporanAgregat $model */

$this->title = $model->laporan_id;
$this->params['breadcrumbs'][] = ['label' => 'Laporan Agregats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="laporan-agregat-view">

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'tanggal_kerja',
            'nama_barang',
            'nama_kerjaan',
            'kuantitas',

        ],
    ]) ?>

</div>
