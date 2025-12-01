<?php

use app\models\Penggunaan;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */
/** @var app\models\PenggunaanDetail[] $penggunaanDetails */

$this->title = 'Detail Penggunaan Kode: ' . $model->getFormattedGunaId();
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pc-content">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3">
            <div class="col-md-4">
                <p><strong>Kode Penggunaan:</strong> <?= $model->getFormattedGunaId() ?></p>
                <p><strong>Nama Pengguna:</strong> <?= $model->user ? $model->user->nama_pengguna : '-' ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($model->tanggal) ?></p>
                <p><strong>Total Item:</strong> <?= $model->total_item_penggunaan ?></p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong> <?= $model->getStatusLabel() ?></p>
                <?php if (!empty($model->permintaan_id)): ?>
                    <div><strong>Dari Permintaan:</strong> 
                        <?= Html::a(
                            $model->permintaanPelanggan->generateKodePermintaan(), 
                            ['permintaan-pelanggan/view', 'permintaan_id' => $model->permintaan_id],
                            ['class' => 'btn btn-sm btn-info']
                        ) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <br>
        <hr>

        <div class="card-body mx-4">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $penggunaanDetails,
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
                        [
                            'attribute' => 'kode_barang',
                            'label' => 'Kode Barang',
                            'value' => function ($model) {
                                return $model->barang ? $model->barang->kode_barang : 'Barang tidak ditemukan';
                            },
                        ],
                        [
                            'attribute' => 'barang_id',
                            'label' => 'Nama Barang',
                            'value' => function ($model) {
                                return $model->barang ? $model->barang->nama_barang : 'Barang tidak ditemukan';
                            },
                        ],
                        [
                            'attribute' => 'jumlah_digunakan',
                            'label' => 'Quantity (Gram)',
                            'value' => function ($model) {
                                return $model->jumlah_digunakan . ' gr';
                            },
                        ],
                        [
                            'label' => 'Quantity (Kg)',
                            'value' => function ($model) {
                                $kg = $model->jumlah_digunakan / 1000;
                                
                                // Format dengan menghilangkan trailing zeros
                                $formatted = rtrim(rtrim(number_format($kg, 10, '.', ''), '0'), '.');
                                
                                return $formatted . ' kg';
                            },
                        ],
                        [
                            'attribute' => 'catatan',
                            'label' => 'Catatan',
                        ],
                        [
                            'attribute' => 'area_gudang',
                            'label' => 'Area Gudang',
                            'value' => function($model) {
                                if ($model->gudang && $model->gudang->area_gudang) {
                                    return 'Area ' . $model->gudang->area_gudang;
                                }
                                return $model->area_gudang ? 'Area ' . $model->area_gudang : '(not set)';
                            },
                        ],
                    ],
                ]); ?>

                <div class="form-group mb-4">
                    <?php $roleName = Yii::$app->user->identity->roleName; ?>
                    
                    <?php if ($model->status_penggunaan == 1): ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    
                    <?php elseif ($roleName == "Operator" && $model->status_penggunaan == 0): ?>
                        <?= Html::a('Edit', ['update', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        
                    <?php elseif ($roleName == "Gudang" && $model->status_penggunaan == 0): ?>
                        <?= Html::a('Update', ['update-qty', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    
                    <?php elseif ($roleName == "Super Admin" && $model->status_penggunaan == 0): ?>
                        <?= Html::a('Edit', ['update', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Update', ['update-qty', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-danger']) ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                        
                    <?php else: ?>
                        <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>