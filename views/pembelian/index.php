<?php

use app\models\Pembelian;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PembelianSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pembelian';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pembelian', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'pembelian_id',
            'user.nama_pengguna',
            'tanggal',
            'supplier.nama',
            'total_biaya',
            //'langsung_pakai',
            'kode_struk',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Pembelian $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'pembelian_id' => $model->pembelian_id]);
                }
            ],
        ],
    ]); ?>


</div>