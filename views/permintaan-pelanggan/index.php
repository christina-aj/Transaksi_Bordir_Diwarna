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

// tombol test mode

// Cek apakah dalam mode testing
// $isTestMode = Yii::$app->request->get('test_mode') == 1;

// Ambil tanggal saat ini
$currentDate = new DateTime();
$day = (int)$currentDate->format('d');

// Periode aktif: 1-10 bulan ini (produksi)
$isActivePeriod = $day <= 10;

// Periode aktif: 1-30 bulan ini (test mode)
$isActivePeriod = $day <= 30;

// Cek apakah bulan ini sudah pernah difinalkan
$session = Yii::$app->session;
$monthKey = 'finalisasi_' . $currentDate->format('Ym');

//untuk produksi
// $alreadyFinalized = $session->get($monthKey, false);

//untuk testing
$alreadyFinalized = false;

// Tombol disabled jika bukan periode aktif ATAU sudah difinalkan
$buttonDisabled = !$isActivePeriod || $alreadyFinalized;

// Informasi bulan yang akan difinalkan
$bulanLalu = date('F Y', strtotime('-1 month'));

?>

<div class="pc-content">
    <div class="card card-table p-4">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="mb-3">
            <?= Html::a('Buat Permintaan Pelanggan', ['create'], ['class' => 'btn btn-success']) ?>

            <!-- Tombol Finalisasi -->
            <?php if ($buttonDisabled): ?>
                <?php if ($alreadyFinalized): ?>
                    <?= Html::button('Permintaan ' . $bulanLalu . ' Sudah Difinalkan', [
                        'class' => 'btn btn-secondary',
                        'disabled' => true,
                    ]) ?>
                <?php else: ?>
                    <?= Html::button('Finalisasi Hanya Aktif Tanggal 1-10 Setiap Bulan', [
                        'class' => 'btn btn-secondary',
                        'disabled' => true,
                    ]) ?>
                <?php endif; ?>
            <?php else: ?>
                <?= Html::a('Finalkan Permintaan ' . $bulanLalu, ['finalkan-bulan-lalu'], [
                    'class' => 'btn btn-warning',
                    'data' => [
                        'confirm' => 'Setelah difinalkan, data ' . $bulanLalu . ' akan dipindah ke Riwayat Penjualan dan status berubah jadi Archived. Data tidak dapat diedit atau dihapus lagi. Yakin ingin melanjutkan?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        </div>

        <!-- Info Periode -->
        <div class="alert alert-info">
            <strong>Info Finalisasi:</strong>
            <ul class="mb-0 mt-2">
                <li>Periode aktif: <strong>Tanggal 1-10 setiap bulan</strong></li>
                <li>Saat ini: <strong><?= date('d F Y') ?></strong></li>
                <li>Status: 
                    <?php if ($isActivePeriod): ?>
                        <span class="badge bg-success">Periode Aktif</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Di Luar Periode</span>
                    <?php endif; ?>
                </li>
                <li>Bulan yang akan difinalkan: <strong><?= $bulanLalu ?></strong></li>
            </ul>
        </div>

        <!-- Filter Tipe Pelanggan -->
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
            <?= Html::a('Polosan Order', ['index', 'tipe_pelanggan' => PermintaanPelanggan::KODE_READY], [
                'class' => $tipe_pelanggan == PermintaanPelanggan::KODE_READY ? 'btn btn-primary' : 'btn btn-outline-primary',
            ]) ?>
        </div>

        <!-- Grid View -->
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'label' => 'Kode Permintaan',
                    'attribute' => 'kode_permintaan',
                    'value' => function ($model) {
                        return $model->generateKodePermintaan();
                    },
                ],
                [
                    'attribute' => 'tanggal_permintaan',
                    'value' => 'tanggal_permintaan',
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
                    'format' => ['date', 'php:d-M-Y'],
                    'headerOptions' => ['style' => 'width:250px'],
                    'enableSorting' => true,
                ],
                [
                    'label' => 'Nama Pelanggan',
                    'attribute' => 'nama_pelanggan',
                    'value' => 'pelanggan.nama_pelanggan',
                ],
                [
                    'label' => 'Total Barang',
                    'attribute' => 'total_item_permintaan',
                ],
                [
                    'label' => 'Status Permintaan',
                    'attribute' => 'status_permintaan',
                    'value' => function ($model) {
                        return $model->getStatusLabel();
                    },
                    'format' => 'raw',
                    'filter' => false,
                ],
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, PermintaanPelanggan $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'permintaan_id' => $model->permintaan_id]);
                    },
                    'visibleButtons' => [
                        'update' => function ($model) {
                            return $model->status_permintaan != 3 && $model->status_permintaan != 2 && $model->status_permintaan != 1;
                        },
                        'delete' => function ($model) {
                            return $model->status_permintaan != 3 && $model->status_permintaan != 1;
                        },
                        'view' => true,
                    ],
                ],
            ],
        ]); ?>

    </div>
</div>

