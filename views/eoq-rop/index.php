<?php

use app\models\EoqRop;
use app\models\Barang;
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
        
        <!-- info detail itung eoq rop -->
        <div class="card-body pb-0">
            <div class="alert alert-light d-flex justify-content-start gap-2 mb-2">
                <div><strong>Info Perhitungan:</strong></div>
                <div>Perhitungan di generate untuk 4 bulan kedepan, untuk barang fast moving, untuk slow moving perhitungan EOQ adalah ROP x 2 dimana ROP == Safety Stock</div>
            </div>
        </div>
                <!-- Info Warna -->
        <div class="card-body pb-0">
            <div class="alert alert-light d-flex justify-content-start gap-2 mb-2">
                <div><strong>Info Warna:</strong></div>
                <div><span class="badge" style="background-color: #dcf1e0ff; color: #155724; padding: 5px 10px;">Fast Moving</span></div>
                <!-- <div><span class="badge" style="background-color: #fff3cd; color: #856404; padding: 5px 10px;">Slow Moving</span></div> -->
                <div><span class="badge" style="background-color: #f9dddfff; color: #721c24; padding: 5px 10px;">Slow Moving</span></div>
            </div>
        </div>
        


        

        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    // Tambahkan rowOptions untuk memberikan warna berbeda per baris
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        $kategori = $model->barang->kategori_barang ?? null;
                        
                        switch ($kategori) {
                            case Barang::KATEGORI_FAST_MOVING:
                                return ['class' => 'fast-moving-row'];
                            case Barang::KATEGORI_SLOW_MOVING:
                                return ['class' => 'slow-moving-row'];
                            // case Barang::KATEGORI_NON_MOVING:
                            //     return ['class' => 'non-moving-row'];
                            default:
                                return [];
                        }
                    },
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

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
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Fast Moving - Hijau muda */
.fast-moving-row {
    background-color: #dcf1e0ff !important;
}

.fast-moving-row:hover {
    background-color: #cde8d3ff !important;
}

/* Slow Moving - Kuning muda */
.slow-moving-row {
    background-color: #f9dddfff !important;
}

.slow-moving-row:hover {
    background-color: #f8d5d8ff !important;
}

/* Non Moving - Merah muda */
/* .non-moving-row {
    background-color: #f8d7da !important;
} */

/* .non-moving-row:hover {
    background-color: #f5c6cb !important;
} */

/* Badge styling */
/* .badge {
    font-size: 0.75rem;
    padding: 0.25em 0.6em;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
} */
</style>