<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var yii\web\View $this */

$this->title = 'Dashboard';

$url = Url::to(['laporan-agregat/get-aggregated-data']);

$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', [
    'position' => View::POS_HEAD
]);

$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js', [
    'position' => View::POS_HEAD
]);
?>

<div class="pc-content">
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card bg-grd-primary order-card">
                <div class="card-body">
                    <h6 class="text-white">Stock Gudang</h6>
                    <h2 class="text-end text-white"><i class="feather icon-shopping-cart float-start"></i><span>486</span>
                    </h2>
                    <!-- <p class="m-b-0">Completed Orders<span class="float-end">351</span></p> -->
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-grd-success order-card">
                <div class="card-body">
                    <h6 class="text-white">Stock Produksi</h6>
                    <h2 class="text-end text-white"><i class="feather icon-tag float-start"></i><span>1641</span>
                    </h2>
                    <!-- <p class="m-b-0">This Month<span class="float-end">213</span></p> -->
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6 col-xl-3">
            <div class="card bg-grd-warning order-card">
                <div class="card-body">
                    <h6 class="text-white">Revenue</h6>
                    <h2 class="text-end text-white"><i class="feather icon-repeat float-start"></i><span>$42,562</span></h2>
                    <p class="m-b-0">This Month<span class="float-end">$5,032</span></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card bg-grd-danger order-card">
                <div class="card-body">
                    <h6 class="text-white">Total Profit</h6>
                    <h2 class="text-end text-white"><i class="feather icon-award float-start"></i><span>$9,562</span></h2>
                    <p class="m-b-0">This Month<span class="float-end">$542</span></p>
                </div>
            </div>
        </div>
     -->
        <div class="col-md-6 col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5>Wilayah yang pernah Order di Diwarna</h5>
                </div>
                <div class="card-body">
                    <div id="map" class="set-map" style="height:365px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-5">
            <div class="card">
                <div class="card-header">
                    <h5>Total Produksi</h5>
                </div>
                <div class="card-body">
                    <canvas id="productionChart" style="width: 100%; height: 365px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Order</h5>
                </div>
                <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => new \yii\data\ArrayDataProvider([
                            'allModels' => $pesanDetails,
                            'pagination' => false, // Sesuaikan jika tidak menggunakan pagination
                        ]),
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn', 'header' => 'No'],
                            [
                                'attribute' => 'kode_barang',
                                'label' => 'Kode Barang',
                                'value' => function ($model) {
                                    if ($model->barang) {
                                        return $model->barang->kode_barang;
                                    }
                                    return 'Barang tidak ditemukan';
                                },
                            ],
                            [
                                'attribute' => 'barang_id',
                                'label' => 'Nama Barang',
                                'value' => function ($model) {
                                    if ($model->barang) {
                                        return $model->barang->nama_barang;
                                    }
                                    return 'Barang tidak ditemukan';
                                },
                            ],
                            [
                                'attribute' => 'qty',
                                'label' => 'Quantity Pesan',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => 'datetime',
                                'label' => 'Dibuat Pada',
                            ],
                        ],
                    ]); ?>

                </div>
            </div>
        </div>
        <!-- <div class="col-md-6 col-xl-5">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <h5>Agregat Produksi</h5>
                    <div class="dropdown">
                        <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone f-18">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">View</a>
                            <a class="dropdown-item" href="#">Edit</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="media align-items-center">
                        <div class="avtar avtar-s bg-light-primary flex-shrink-0">
                            <i class="ph ph-money f-20"></i>
                        </div>
                        <div class="media-body ms-3">
                            <p class="mb-0 text-muted">Total Produksi</p>
                            <h5 class="mb-0">556</h5>
                        </div>
                    </div>
                    <div id="earnings-users-chart"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s bg-grd-primary flex-shrink-0">
                                    <i class="ph ph-money f-20 text-white"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <p class="mb-0 text-muted">Total Produksi</p>
                                    <h6 class="mb-0">556</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="media align-items-center">
                                <div class="avtar avtar-s bg-grd-success flex-shrink-0">
                                    <i class="ph ph-shopping-cart text-white f-20"></i>
                                </div>
                                <div class="media-body ms-2">
                                    <p class="mb-0 text-muted">Product Sold</p>
                                    <h6 class="mb-0">15,830</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="col-md-4 col-sm-6">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="<?= Yii::getAlias('@web') ?>/assets/images/widget/img-status-4.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                        <h6 class="mb-0">Daily Sales</h6>
                        <div class="dropdown">
                            <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone f-18">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="f-w-300 d-flex align-items-center m-b-0">$249.95</h3>
                        <span class="badge bg-light-success ms-2">36%</span>
                    </div>
                    <p class="text-muted mb-2 text-sm mt-3">You made an extra 35,000 this daily</p>
                    <div class="progress" style="height: 7px">
                        <div class="progress-bar bg-brand-color-1" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card statistics-card-1">
                <div class="card-body">
                    <img src="<?= Yii::getAlias('@web') ?>/assets/images/widget/img-status-5.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                        <h6 class="mb-0">Monthly Sales</h6>
                        <div class="dropdown">
                            <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone f-18">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="f-w-300 d-flex align-items-center m-b-0">$249.95</h3>
                        <span class="badge bg-light-primary ms-2">20%</span>
                    </div>
                    <p class="text-muted mb-2 text-sm mt-3">You made an extra 35,000 this Monthly</p>
                    <div class="progress" style="height: 7px">
                        <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="card statistics-card-1 bg-brand-color-1">
                <div class="card-body">
                    <img src="<?= Yii::getAlias('@web') ?>/assets/images/widget/img-status-6.svg" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center justify-content-between mb-3 drp-div">
                        <h6 class="mb-0 text-white">Yearly Sales</h6>
                        <div class="dropdown">
                            <a class="avtar avtar-xs btn-link-secondary bg-transparent text-white dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone bg-white f-18">more_vert</i></a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="#">View</a>
                                <a class="dropdown-item" href="#">Edit</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <h3 class="text-white f-w-300 d-flex align-items-center m-b-0">$249.95</h3>
                    </div>
                    <p class="text-white text-opacity-75 mb-2 text-sm mt-3">You made an extra 35,000 this Daily</p>
                    <div class="progress" style="height: 7px">
                        <div class="progress-bar bg-brand-color-3" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- Recent Orders start -->

    </div>
</div>

<?php
$script = <<<JS

// Inisialisasi peta dan atur pusatnya di Indonesia
var map = L.map('map').setView([-2.548926, 118.0148634], 5); // Koordinat pusat Indonesia

// Tambahkan layer peta dari OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Contoh marker: Jakarta
L.marker([-6.2088, 106.8456]).addTo(map) // Koordinat Jakarta
    .bindPopup('Jakarta, Indonesia')
    .openPopup();

// Tambahkan marker lain di lokasi-lokasi di Indonesia
var locations = [
    { title: "Yogyakarta", position: [-7.797068, 110.370529] },
    { title: "Manado", position: [1.48218, 124.84899] },
    { title: "Bali", position: [-8.409518, 115.188919] }
];

// Loop untuk menambahkan marker pada setiap lokasi
locations.forEach(function (location) {
    L.marker(location.position).addTo(map)
        .bindPopup(location.title);
});

$(document).ready(function() {
    $('#debugInfo').html('Loading data...');
    
    $.ajax({
        url: '{$url}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            $('#debugInfo').html('Data received. Processing...');
            console.log('Data received:', response);
            
            if (!response || response.length === 0) {
                $('#debugInfo').html('No data received from server');
                return;
            }

           
            const chartData = {
                labels: response.map(item => item.year.toString()),
                datasets: [{
                    label: 'Total Produksi',
                    data: response.map(item => parseInt(item.total_kuantitas)),
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            };

            const config = {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Grafik Produksi per Tahun'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Total Kuantitas'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tahun'
                            }
                        }
                    }
                }
            };

            
            const ctx = document.getElementById('productionChart').getContext('2d');
            new Chart(ctx, config);
            
            $('#debugInfo').html('Chart created successfully');
        },
        error: function(xhr, status, error) {
            $('#debugInfo').html('Error loading data: ' + error);
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response:', xhr.responseText);
        }
    });
});
JS;

$url = Url::to(['laporan-agregat/get-aggregated-data']);
$this->registerJs($script, View::POS_END);
?>