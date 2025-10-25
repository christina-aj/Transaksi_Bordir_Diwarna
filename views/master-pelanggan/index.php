<?php

use app\models\MasterPelanggan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\MasterPelangganSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Data Pelanggan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card card-table p-4">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Create Data Pelanggan', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'pelanggan_id',
                'kode_pelanggan',
                'nama_pelanggan',
                // [
                //     'class' => ActionColumn::className(),
                //     'urlCreator' => function ($action, MasterPelanggan $model, $key, $index, $column) {
                //         return Url::toRoute([$action, 'pelanggan_id' => $model->pelanggan_id]);
                //      }
                // ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view-data} {update} {delete}',
                    'buttons' => [
                        'view-data' => function ($url, $model) {
                            return Html::a('<i class="fas fa-eye"></i> Lihat Data', ['master-pelanggan/view', 'pelanggan_id' => $model->pelanggan_id], [
                                'class' => 'btn btn-primary btn-sm',
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>

    </div>
</div>
