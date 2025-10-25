<?php

use app\models\PermintaanPelanggan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\daterange\DateRangePicker;

/** @var yii\web\View $this */
/** @var app\models\PermintaanPelangganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Permintaan Pelanggan';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <div class="card card-table p-4">

        <h1><?= Html::encode($this->title) ?></h1>

        <div>
            <?= Html::a('Buat Permintaan Pelanggan', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div style="margin-top: 5px; margin-bottom: 10px;">
            <?php
            $tipe_pelanggan = Yii::$app->request->get('tipe_pelanggan', 'all');
            ?>
            <?= Html::a('Semua', ['index', 'tipe_pelanggan' => 'all'], [
                'class' => $tipe_pelanggan == 'all' ? 'btn btn-primary' : 'btn btn-outline-primary',
            ]) ?>
            <?= Html::a('Custom Order', ['index', 'tipe_pelanggan' => PermintaanPelanggan::KODE_CUSTOM], [
                'class' => $tipe_pelanggan == PermintaanPelanggan::KODE_CUSTOM ? 'btn btn-primary' : 'btn btn-outline-primary',
            ]) ?>
            <?= Html::a('Ready Stock Order', ['index', 'tipe_pelanggan' => PermintaanPelanggan::KODE_READY], [
                'class' => $tipe_pelanggan == PermintaanPelanggan::KODE_READY ? 'btn btn-primary' : 'btn btn-outline-primary',
            ]) ?>
        </div>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'permintaan_id',
                [
                    'label' => 'Kode Permintaan',
                    'attribute' => 'kode_permintaan',
                    'value' => function ($model) {
                        return $model->generateKodePermintaan();
                    },
                ],
                [
                    'attribute' => 'tanggal_permintaan',
                    'value' => 'tanggal_permintaan', // Menampilkan kolom tanggal
                    'label' => 'Tanggal',
                    'filter' => DateRangePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'tanggal_permintaan',
                        'convertFormat' => true,
                        'pluginOptions' => [
                            'locale' => [
                                'format' => 'd-m-Y',
                                'separator' => ' - ',
                            ],
                            'autoUpdateInput' => false,
                            'opens' => 'left',
                        ],
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Pilih rentang tanggal'
                        ]
                    ]),
                    'format' => ['date', 'php:d-M-Y'], // Format tampilan kolom tanggal
                    'headerOptions' => ['style' => 'width:250px'], // Tambahkan lebar jika diperlukan
                    'enableSorting' => true, // Mengaktifkan sorting untuk kolom tanggal
                ],
                [
                    'label' => 'Nama Pelanggan',
                    'attribute' => 'nama_pelanggan',
                    'value' => 'pelanggan.nama_pelanggan',
                ],
                // 'pelanggan_id',
                // 'tipe_pelanggan',
                // 'total_item_permintaan',
                [
                    'label' => 'Total Barang',
                    'attribute' => 'total_item_permintaan',
                ],
                // 'tanggal_permintaan',
                // 'status_permintaan',
                [
                    'label' => 'Status Permintaan',
                    'attribute' => 'status_permintaan',
                    'value' => function ($model) {
                        return $model->getStatusLabel();
                    },
                    'format' => 'raw',
                    'filter' => false,
                ],
                //'created_at',
                //'updated_at',
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, PermintaanPelanggan $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'permintaan_id' => $model->permintaan_id]);
                    }
                ],
            ],
        ]); ?>


    </div>
</div>
