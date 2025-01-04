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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'Nama Operator - Shift',
                'value' => function($model) {
                    $shiftTime = ($model['shift'] == "1") ? 'Pagi' : 'Sore';
                    return $model->shift->nama_operator. ' (' . $shiftTime . ')';
                }
            ],
            [
                'attribute' => 'tanggal_kerja',
                'value' => function($model) {
                    return Yii::$app->formatter->asDate($model->tanggal_kerja, 'php:d-m-Y');
                },
            ],
            'nama_kerjaan',
            'nama_barang',
            'vs',
            'stitch',
            'kuantitas',
            'bs',
            'berat',
        ],
    ]) ?>
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
</div>

<script>
        document.addEventListener("DOMContentLoaded", function() {
            const cells = document.querySelectorAll('td, th');
            cells.forEach(cell => {
                if (cell.textContent.trim() === '(not set)') {
                    cell.textContent = 'kosong';
                }
            });
        });
    </script>