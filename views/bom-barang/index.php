<?php

use app\models\BomBarang;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\BomBarangSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Data BOM Barang Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Bom Barang', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label' => "Nama Barang Produksi",
                            'attribute' => 'barang_produksi_id',
                            'value' => function ($model) {
                                return $model->barangProduksi->nama;
                            },
                        ],

                        // 'BOM_barang_id',
                        // 'barang_produksi_id',
                        'total_bahan_baku',
                        // 'created_at',
                        // 'updated_at',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, BomBarang $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'BOM_barang_id' => $model->BOM_barang_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
