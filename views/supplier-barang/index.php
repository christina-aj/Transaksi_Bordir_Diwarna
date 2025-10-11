<?php

use app\models\SupplierBarang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Supplier Barangs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-barang-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Supplier Barang', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'supplier_barang_id',
            'barang_id',
            'supplier_id',
            'lead_time',
            'harga_per_kg',
            //'created_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SupplierBarang $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'supplier_barang_id' => $model->supplier_barang_id]);
                 }
            ],
        ],
    ]); ?>


</div>
