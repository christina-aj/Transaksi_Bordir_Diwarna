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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1><?= Html::encode($this->title) ?></h1>
            <div>
                <?= Html::a('<i class="fas fa-sync-alt"></i> Generate EOQ ROP', ['create'], [
                'class' => 'btn btn-primary me-2',
                'data' => [
                    'confirm' => 'Generate perhitungan EOQ ROP baru untuk semua periode?',
                    'method' => 'post',
                ]]) ?>
            </div>
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
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<strong>' . $model->barang->nama_barang . '</strong>';
                            }
                        ],

                        [
                            'attribute' => 'periode',
                            'label' => 'Periode',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<strong>' . $model->getPeriodeFormatted() . '</strong>';
                            }
                        ],
                        
                        // 'periode',
                        // 'demand_snapshot',
                        [
                            'attribute' => 'demand_snapshot',
                            'label' => 'Demand',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->demand_snapshot, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'attribute' => 'biaya_pesan_snapshot',
                            'label' => 'Biaya Pesan',
                            'value' => function($model) {
                                return 'Rp ' . number_format($model->biaya_pesan_snapshot, 0, ',', '.');
                            },
                        ],
                        [
                            'attribute' => 'biaya_simpan_snapshot',
                            'label' => 'Biaya Simpan',
                            'value' => function($model) {
                                return 'Rp ' . number_format($model->biaya_simpan_snapshot, 0, ',', '.');
                            },
                        ],
                        [
                            'attribute' => 'safety_stock_snapshot',
                            'label' => 'Safety Stock',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->safety_stock_snapshot, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'attribute' => 'lead_time_snapshot',
                            'label' => 'Lead Time',
                            'value' => function($model) {
                                return $model->lead_time_snapshot . ' hari';
                            },
                        ],
                        [
                            'attribute' => 'hasil_eoq',
                            'label' => 'EOQ',
                            'format' => 'raw',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return '<strong>' . number_format($model->hasil_eoq, 0, ',', '.') . ' ' . $satuan . '</strong>';
                            },
                        ],
                        [
                            'attribute' => 'hasil_rop',
                            'label' => 'ROP',
                            'format' => 'raw',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return '<strong>' . number_format($model->hasil_rop, 0, ',', '.') . ' ' . $satuan . '</strong>';
                            },
                        ],  

                        // 'total_biaya_persediaan',
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
