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
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'laporan_id',
            'mesin_id',
            [
                'label' => 'Shift ID - Nama Operator',
                'value' => function($model) {
                    return $model->shift_id . ' - ' . $model->shift->nama_operator;
                }
            ],
            [
                'attribute' => 'tanggal_kerja',
                'value' => function($model) {
                    return Yii::$app->formatter->asDate($model->tanggal_kerja, 'php:d-m-yy');
                },
            ],
            'nama_kerjaan',
            'vs',
            'stitch',
            'kuantitas',
            'bs',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, LaporanProduksi $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'laporan_id' => $model->laporan_id]);
                 }
            ],
        ],
    ]); ?>

</div>
