<?php

use app\models\PesanDetail;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PesanDetailSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pesan Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pesan Detail', ['pesan-detail/create-pemesanan'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'pesandetail_id',
            'pemesanan_id' =>
            [
                'label' => 'Kode Pemesanan',
                'attribute' => 'pemesanan_id',
                'value' => function ($model) {
                    return $model->getFormattedOrderId(); // Call the method to get the formatted ID
                },
            ],
            // 'barang_id',
            'barang.nama_barang' => [
                'label' => 'Nama barang',
                'attribute' => 'barang_id',
                'value' => function ($model) {
                    $barang = $model->barang;
                    $unit = $barang->unit;
                    return $barang->kode_barang . ' - ' . $barang->nama_barang . ' - ' . $barang->angka . ' ' . ($unit ? $unit->satuan : 'Satuan tidak ditemukan') . ' - ' . $barang->warna;
                }
            ],
            'qty' => [
                'label' => 'Quantity pesan',
                'attribute' => 'qty',
                'filter' => false
            ],
            'qty_terima' => [
                'label' => 'Quantity terima',
                'attribute' => 'qty_terima',
                'filter' => false
            ],
            'catatan',
            'langsung_pakai' => [
                'label' => 'langsung Pakai',
                'attribute' => 'langsung_pakai',
                'filter' => [
                    1 => 'Langsung Pakai',
                    0 => 'Tidak Langsung Pakai',
                ],
                'filterInputOptions' => [
                    'class' => 'form-control',
                    'prompt' => 'Pilih Pemakaian',
                ],
                'format' => 'raw', // This allows for raw HTML output (for icons)
                'value' => function ($model) {
                    // Check the value of the status field
                    if ($model->langsung_pakai == 1) {
                        // Active status (1)
                        return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                    } else {
                        // Inactive status (0)
                        return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                    }
                },
            ],
            'is_correct' => [
                'label' => 'Barang Lengkap',
                'attribute' => 'is_correct',
                'filter' => false,
                'format' => 'raw', // This allows for raw HTML output (for icons)
                'value' => function ($model) {
                    // Check the value of the status field
                    if ($model->is_correct == 1) {
                        // Active status (1)
                        return Html::tag('span', '&#10004;', ['style' => 'color: green; font-size: 20px;']); // Checkmark icon
                    } else {
                        // Inactive status (0)
                        return Html::tag('span', '&#10008;', ['style' => 'color: red; font-size: 20px;']); // Cross icon
                    }
                },
            ],
            //'created_at',
            //'update_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PesanDetail $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pesandetail_id' => $model->pesandetail_id]);
                }
            ],
        ],
    ]); ?>


</div>