<?php

use app\models\Penggunaan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PenggunaanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Penggunaan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Penggunaan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'penggunaan_id',
            [
                'attribute' => 'tanggal_digunakan',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format menjadi dd-mm-yyyy
            ],
            // 'barang_id',
            'user.nama_pengguna',
            'barang.kode_barang',
            'barang.nama_barang',
            'jumlah_digunakan',
            // 'tanggal_digunakan',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Penggunaan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'penggunaan_id' => $model->penggunaan_id]);
                }
            ],
        ],
    ]); ?>


</div>