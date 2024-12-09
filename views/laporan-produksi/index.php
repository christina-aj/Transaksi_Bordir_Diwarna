<?php

use app\models\LaporanProduksi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\LaporanProduksisearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Laporan Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Laporan Produksi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'mesin_id',
                'label' => 'Nama Mesin',
                'value' => function ($model) {
                    return $model->mesin->nama;
                },
            ],
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
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LaporanProduksi $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'laporan_id' => $model->laporan_id]);
                 }
            ],
        ],
    ]); ?>

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

</div>
