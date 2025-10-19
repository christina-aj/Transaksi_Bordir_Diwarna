<?php

use app\models\SupplierBarang;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SupplierBarangSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Data Supplier Barang';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Supplier Barang', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'supplier_barang_id',
                        [
                            'label' => "Nama Barang",
                            'attribute' => 'barang_id',
                            'value' => function ($model) {
                                return $model->barang->nama_barang;
                            },
                        ],
                        // 'barang_id',
                        'total_supplier_barang',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, SupplierBarang $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'supplier_barang_id' => $model->supplier_barang_id]);
                             }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>