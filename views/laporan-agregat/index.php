<?php
use kartik\date\DatePicker; // Import DatePicker
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var array $aggregatedData */

$this->title = 'Laporan Agregat Bulanan';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pc-content">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <!-- Filter Button -->
    <?= Html::button('Filter Data', ['class' => 'btn btn-primary', 'id' => 'filter-button']) ?>
    
    <!-- Combined Button for Date Range -->
    <?= Html::button('Input Tanggal Awal dan Akhir', ['class' => 'btn btn-info', 'id' => 'date-range-button']) ?>
    
   <!-- Modal for Filter -->
    <?php
    Modal::begin([
        'title' => '<h4>Filter Laporan Agregat</h4>',
        'id' => 'filter-modal',
        'size' => 'modal-lg',
    ]);
    ?>

    <div id="filterModalContent">
        <form id="filter-form" method="get" action="<?= Url::to(['laporan-agregat/index']) ?>"> <!-- Correct URL for action -->
            <div class="form-group">
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

    <!-- Modal for Date Range Input -->
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
                    'options' => ['placeholder' => 'Pilih Tanggal Awal', 'readonly' => true],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy', // Set format to d-m-y
                        'autoclose' => true,
                    ],
                ]); ?>
                </div>
                <div class="form-group">
                    <label for="end_date">Tanggal Akhir</label>
                    <?= DatePicker::widget([
                        'name' => 'end_date',
                        'options' => ['placeholder' => 'Pilih Tanggal Akhir', 'readonly' => true],
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy', // Set format to d-m-y
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
    
    <?= Html::a('Kembali ke Normal', Url::to(['laporan-agregat/index']), ['class' => 'btn btn-secondary', 'id' => 'reset-button-date']) ?>

    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $aggregatedData,
            'pagination' => [
                'pageSize' => 20,   
            ],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'year', 'label' => 'Tahun'],
            ['attribute' => 'month', 'label' => 'Bulan'],
            ['attribute' => 'nama_barang', 'label' => 'Nama Barang'],
            ['attribute' => 'nama_kerjaan', 'label' => 'Job Name'],
            ['attribute' => 'total_kuantitas', 'label' => 'Qty', 'format' => ['integer']],
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