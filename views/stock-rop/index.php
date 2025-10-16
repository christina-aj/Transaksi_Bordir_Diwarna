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
                <?= Html::a('🔄 Generate Data', ['generate'], [
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
                        'periode',
                        [
                            'label' => 'Stock Barang',
                            'attribute' => 'stock_barang',
                            'format' => ['decimal', 0],
                        ],
                        [
                            'label' => 'Safety Stock',
                            'attribute' => 'safety_stock',
                            'format' => ['decimal', 0],
                        ],
                        [
                            'label' => 'EOQ',
                            'attribute' => 'jumlah_eoq',
                            // 'format' => ['decimal', 2],
                        ],
                        [
                            'label' => 'ROP',
                            'attribute' => 'jumlah_rop',
                            // 'format' => ['decimal', 2],
                        ],
                        [
                            'label' => 'Status',
                            'format' => 'raw',
                            'value' => function($model) {
                                $status = $model->statusPesan; // Pakai method dari model
                                
                                $class = 'success';
                                if ($status == 'Pesan Sekarang') {
                                    $class = 'danger';
                                } elseif ($status == 'Perlu Diperhatikan') {
                                    $class = 'warning';
                                }
                                
                                return '<span class="badge bg-' . $class . '">' . htmlspecialchars($status) . '</span>';
                            },
                        ],
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'stock_rop_id' => $model->stock_rop_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>