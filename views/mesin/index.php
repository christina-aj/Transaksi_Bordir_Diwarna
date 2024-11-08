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
?>
<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Mesin', ['create'], ['class' => 'btn btn-success']) ?>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'nama',
                        'deskripsi:ntext',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Mesin $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'mesin_id' => $model->mesin_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>