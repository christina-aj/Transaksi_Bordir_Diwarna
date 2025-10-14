<?php

use app\models\EoqRop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use app\helpers\ModelHelper;

/** @var yii\web\View $this */
/** @var app\models\EoqRopSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Perhitungan EOQ ROP';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('generate EOQ ROP', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,

                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'EOQ_ROP_id',

                        // 'barang_id',
                        [
                            'attribute' => 'barang_id',
                            'label' => 'Nama Barang',
                            'value' => function($model) {
                                return $model->barang->nama_barang ?? '';
                            }
                        ],

                        [
                            'attribute' => 'periode',
                            'label' => 'Periode',
                            'value' => function($model) {
                                return $model->getPeriodeFormatted();
                            }
                        ],
                        
                        // 'periode',
                        'total_bom',
                        'biaya_pesan_snapshot',
                        'biaya_simpan_snapshot',
                        'safety_stock_snapshot',
                        'lead_time_snapshot',
                        'demand_snapshot',
                        // 'total_biaya_persediaan',
                        'hasil_eoq',
                        'hasil_rop',
                        //'created_at',
                        // [
                        //     'class' => ActionColumn::className(),
                        //     'urlCreator' => function ($action, EoqRop $model, $key, $index, $column) {
                        //         return Url::toRoute([$action, 'EOQ_ROP_id' => $model->EOQ_ROP_id]);
                        //      }
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
