<?php

use app\models\Barangproduksi;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Barangproduksisearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Barang Produksi';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success" style="margin-top: 3px;">
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger" style="margin-top: 3px;">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Barang Produksi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'barang_produksi_id',
            'kode_barang_produksi',
            'nama',
            // 'nama_jenis',
            'ukuran',
            'deskripsi:ntext',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, Barangproduksi $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'barang_produksi_id' => $model->barang_produksi_id]);
            //      }
            // ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view-data}{delete}',
                'buttons' => [
                    'view-data' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i> Lihat BOM', ['barangproduksi/view', 'barang_produksi_id' => $model->barang_produksi_id], [
                            'class' => 'btn btn-primary btn-sm me-1',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', ['delete', 'barang_produksi_id' => $model->barang_produksi_id], [
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Delete',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
