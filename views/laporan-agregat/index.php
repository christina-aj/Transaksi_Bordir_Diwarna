<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $aggregatedData */

$this->title = 'Laporan Agregat';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <h1><?= Html::encode($this->title) ?></h1>


    <?= Html::button('Filter Data', ['class' => 'btn btn-primary', 'id' => 'filter-button']) ?>


    <?= Html::button('Input Tanggal Awal dan Akhir', ['class' => 'btn btn-info', 'id' => 'date-range-button']) ?>


    <?= Html::a('Kembali ke Normal', Url::to(['laporan-agregat/index']), ['class' => 'btn btn-secondary', 'id' => 'reset-button-date']) ?>


    <?php
    Modal::begin([
        'title' => '<h4>Filter Laporan Agregat</h4>',
        'id' => 'filter-modal',
        'size' => 'modal-lg',
    ]);
    ?>

    <div id="filterModalContent">
        <form id="filter-form" method="get" action="<?= Url::to(['laporan-agregat/index']) ?>">
            <label for="year">Tahun</label>
            <input type="number" name="year" class="form-control" id="year">
    </div>

    <div class="form-group">
        <label for="month">Bulan</label>
        <input type="number" name="month" class="form-control" id="month">
    </div>

    <div class="form-group">
        <label for="nama_kerjaan">Nama Kerjaan</label>
        <input type="text" name="nama_kerjaan" class="form-control" id="nama_kerjaan">
    </div>

    <div class="modal-footer">
        <button type="submit" class="btn btn-success">Filter Data</button>
        <?= Html::a('Kembali ke Normal', Url::to(['laporan-agregat/index']), ['class' => 'btn btn-secondary', 'id' => 'reset-button']) ?>
    </div>
    </form>
</div>

<?php Modal::end(); ?>

<?php
Modal::begin([
    'title' => '<h4>Input Tanggal Awal dan Akhir</h4>',
    'id' => 'date-range-modal',
    'size' => 'modal-lg',
]);

?>

<div class="form-group">
    <form id="filter-form" method="get" action="<?= Url::to(['laporan-agregat/index']) ?>">
        <div class="form-group">
            <label for="start_date">Tanggal Awal</label>
            <?= DatePicker::widget([
                'name' => 'start_date',
                'options' => [
                    'placeholder' => 'Pilih Tanggal Awal',
                    'readonly' => true,
                    'id' => 'start-date-picker'
                ],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true,
                ],
            ]); ?>
        </div>

        <div class="form-group">
            <label for="end_date">Tanggal Akhir</label>
            <?= DatePicker::widget([
                'name' => 'end_date',
                'options' => [
                    'placeholder' => 'Pilih Tanggal Akhir',
                    'readonly' => true,
                    'id' => 'end-date-picker'
                ],
                'pluginOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true,
                ],
            ]); ?>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Filter Data</button>
        </div>
    </form>
</div>


<?php Modal::end(); ?>



<?= GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => $aggregatedData,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]),
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'year',
            'label' => 'Tahun'
        ],
        [
            'attribute' => 'month',
            'label' => 'Bulan'
        ],
        [
            'attribute' => 'nama_barang',
            'label' => 'Nama Barang'
        ],
        [
            'attribute' => 'nama_kerjaan',
            'label' => 'Job Name'
        ],
        [
            'attribute' => 'total_kuantitas',
            'label' => 'Qty',
            'format' => ['integer']
        ],
    ],
]); ?>
</div>

<?php
$script = <<< JS
   
    $('#filter-button').on('click', function () {
        $('#filter-modal').modal('show');
    });

    $('#date-range-button').on('click', function () {
        $('#date-range-modal').modal('show');
    });

    $('#start-date-picker').on('changeDate', function (e) {
        var startDate = $(this).datepicker('getDate');
        if (startDate) {
           
            var formattedDate = ('0' + startDate.getDate()).slice(-2) + '-' +
                                ('0' + (startDate.getMonth() + 1)).slice(-2) + '-' +
                                startDate.getFullYear();
                                
            
            $('#end-date-picker').datepicker('setStartDate', formattedDate);
        }
    });

    $('#date-range-form').on('submit', function (e) {
        var startDate = $('input[name="start_date"]').val();
        var endDate = $('input[name="end_date"]').val();
        
        if (new Date(startDate.split('-').reverse().join('-')) > new Date(endDate.split('-').reverse().join('-'))) {
            e.preventDefault();
            $('#error-message').text('Tanggal Akhir tidak boleh lebih awal dari Tanggal Awal.').show();
        } else {
            $('#error-message').hide();
        }
    });
    JS;
$this->registerJs($script);
?>