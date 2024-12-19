<?php

use app\models\Mesin;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Mesinsearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Mesin';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user->identity->role;
?>
<div class="pc-content">

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

    <div class="card card-table">
        <?php if ($user !== 'Operator'): ?>
            <div class="card-header">
                <h1><?= Html::encode($this->title) ?></h1>
                <?= Html::a('Create Mesin', ['create'], ['class' => 'btn btn-success']) ?>

            </div>
        <?php endif; ?>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'nama',
                        [
                            'attribute' => 'kategori',
                            'value' => function ($model) {
                                return $model->kategori == 1 ? 'Bordir' : ($model->kategori == 2 ? 'Kaos Kaki' : 'Tidak diketahui');
                            },
                            'filter' => [
                                1 => 'Bordir',
                                2 => 'Kaos Kaki',
                            ],
                        ],
                        'deskripsi:ntext',
                        [
                            'class' => ActionColumn::className(),
                            'template' => '{view}',  // Hanya tampilkan tombol 'view' saja
                            'urlCreator' => function ($action, Mesin $model, $key, $index, $column) {
                                if (Yii::$app->user->identity->role === 'Operator') {
                                    // Jika Operator, hanya izinkan akses ke view
                                    if ($action === 'update' || $action === 'delete') {
                                        return null; // Mengembalikan null untuk mencegah akses ke edit dan delete
                                    }
                                }
                                return Url::toRoute([$action, 'mesin_id' => $model->mesin_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>

    </div>
</div>