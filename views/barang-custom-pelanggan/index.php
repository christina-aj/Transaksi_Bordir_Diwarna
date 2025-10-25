<?php

use app\models\BarangCustomPelanggan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BarangCustomPelangganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Barang Custom Pelanggans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-custom-pelanggan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Barang Custom Pelanggan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'barang_custom_pelanggan_id',
            'pelanggan_id',
            'kode_barang_custom',
            'nama_barang_custom',
            'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, BarangCustomPelanggan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'barang_custom_pelanggan_id' => $model->barang_custom_pelanggan_id]);
                 }
            ],
        ],
    ]); ?>


</div>
