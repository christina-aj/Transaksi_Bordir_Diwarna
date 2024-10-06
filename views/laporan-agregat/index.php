<?php
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
    

    <!-- Modal for Filter -->
    <?php
        Modal::begin([
        'title' => '<h4>Filter Laporan Agregat</h4>',
        'id' => 'filter-modal',
        'size' => 'modal-lg',
        ]);

        echo '<div id="filterModalContent">';
        echo '<form id="filter-form" method="get" action="' . Url::to(['laporan-agregat/index']) . '">'; // Correct URL for action
        echo '<div class="form-group">';
        echo Html::label('Tahun', 'year');
        echo Html::input('number', 'year', null, ['class' => 'form-control', 'id' => 'year']);
        echo '</div>';
        echo '<div class="form-group">';
        echo Html::label('Bulan', 'month');
        echo Html::input('number', 'month', null, ['class' => 'form-control', 'id' => 'month']);
        echo '</div>';
        echo '<div class="form-group">';
        echo Html::label('Nama Kerjaan', 'nama_kerjaan');
        echo Html::textInput('nama_kerjaan', '', ['class' => 'form-control', 'id' => 'nama_kerjaan']);
        echo '</div>';
        echo Html::submitButton('Filter', ['class' => 'btn btn-success']);
        echo '</form>';
        echo '</div>';

        Modal::end();
    ?>
    
    <?php
    echo Html::a('Back to Normal View', Url::to(['laporan-agregat/index']), ['class' => 'btn btn-secondary']);
    ?>

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
JS;
$this->registerJs($script);
?>
