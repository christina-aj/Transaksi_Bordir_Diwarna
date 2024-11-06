<?php


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
                    <p class="m-b-0">Completed Orders<span class="float-end">351</span></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="card bg-grd-success order-card">
                <div class="card-body">
                    <h6 class="text-white">Stock Produksi</h6>
                    <h2 class="text-end text-white"><i class="feather icon-tag float-start"></i><span>1641</span>
                    </h2>
                    <p class="m-b-0">This Month<span class="float-end">213</span></p>
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
                    <div id="world-map-markers" class="set-map" style="height:365px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-7">
            <div class="card">
                <div class="card-header">
                    <h5>Total Produksi</h5>
                </div>
                <div class="card-body">
                    <canvas id="productionChart" style="width: 100%; height: 400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<<JS
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