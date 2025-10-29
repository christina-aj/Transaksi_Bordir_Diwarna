<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Laporan Stock ROP';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <div class="card card-table">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1><?= Html::encode($this->title) ?></h1>
            <div>
                <?= Html::a('<i class="fas fa-sync-alt"></i> Generate Data', ['generate'], [
                    'class' => 'btn btn-primary me-2',
                    'data' => [
                        'confirm' => 'Generate data Stock ROP dari tabel EOQ_ROP?',
                        'method' => 'post',
                    ],
                ]) ?>
                <!-- <?= Html::a('➕ Create Stock ROP', ['create'], ['class' => 'btn btn-success']) ?> -->
            </div>
        </div>
        <div class="card-body">
            <?php if (Yii::$app->session->hasFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= Yii::$app->session->getFlash('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'emptyText' => '<div class="alert alert-warning">
                        <strong>Tidak ada data!</strong><br>
                        Klik tombol <strong>"Generate Data"</strong> untuk membuat data Stock ROP dari tabel EOQ_ROP.
                    </div>',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'label' => 'Kode Barang',
                            'value' => function($model) {
                                return $model->barang ? $model->barang->kode_barang : '-';
                            }
                        ],
                        [
                            'label' => 'Nama Barang',
                            'value' => function($model) {
                                return $model->barang ? $model->barang->nama_barang : '-';
                            }
                        ],
                        // 'periode',
                        [
                            'attribute' => 'periode',
                            'label' => 'Periode',
                            'format' => 'raw',
                            'value' => function($model) {
                                return $model->getPeriodeFormatted();
                            }
                        ],
                        [
                            'label' => 'Stock Barang',
                            'attribute' => 'stock_barang',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->stock_barang, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'label' => 'Safety Stock',
                            'attribute' => 'safety_stock',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->safety_stock, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'label' => 'EOQ',
                            'attribute' => 'jumlah_eoq',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->jumlah_eoq, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'label' => 'ROP',
                            'attribute' => 'jumlah_rop',
                            'value' => function($model) {
                                $satuan = $model->barang->unit->satuan;
                                return number_format($model->jumlah_rop, 0, ',', '.') . ' ' . $satuan;
                            },
                        ],
                        [
                            'label' => 'Status',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width: 150px; text-align: center;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'value' => function($model) {
                                $status = $model->statusPesan;
                                
                                $class = 'success';
                                $icon = 'check-circle';
                                if ($status == 'Pesan Sekarang') {
                                    $class = 'danger';
                                    $icon = 'exclamation-circle';
                                } elseif ($status == 'Perlu Diperhatikan') {
                                    $class = 'warning';
                                    $icon = 'exclamation-triangle';
                                }
                                
                                return '<span class="badge bg-' . $class . '"><i class="fas fa-' . $icon . '"></i> ' . htmlspecialchars($status) . '</span>';
                            },
                        ],
                        [
                            'label' => 'Aksi',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width: 100px; text-align: center;'],
                            'contentOptions' => ['style' => 'text-align: center;'],
                            'value' => function($model) {
                                if ($model->statusPesan == 'Pesan Sekarang') {
                                    return Html::a(
                                        '<i class="fas fa-cart-plus"></i>', 
                                        ['pemesanan/create', 'stock_rop_id' => $model->stock_rop_id],
                                        [
                                            'class' => 'btn btn-sm btn-danger',
                                            'title' => 'Buat Pemesanan',
                                            'data-bs-toggle' => 'tooltip'
                                        ]
                                    );
                                }
                                return '<span class="text-muted text-center d-block">-</span>';
                            },
                        ],

                        // [
                        //     'class' => ActionColumn::className(),
                        //     'urlCreator' => function ($action, $model, $key, $index, $column) {
                        //         return Url::toRoute([$action, 'stock_rop_id' => $model->stock_rop_id]);
                        //     }
                        // ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>