<?php

use app\models\SupplierBarangDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Supplier Barang Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-barang-detail-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Supplier Barang Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'supplier_barang_detail_id',
            'supplier_barang_id',
            'supplier_id',
            'lead_time',
            'harga_per_kg',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, SupplierBarangDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'supplier_barang_detail_id' => $model->supplier_barang_detail_id]);
                 }
            ],
        ],
    ]); ?>


</div>
