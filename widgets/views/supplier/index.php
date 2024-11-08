<?php

use app\models\Supplier;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\SupplierSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Suppliers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">
    <div class="card card-table">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?= Html::a('Create Supplier', ['create'], ['class' => 'btn btn-success']) ?>
        </div>
        <div class="card-body">
            <div class="table-resposive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        // 'supplier_id',
                        'nama',
                        'notelfon',
                        'alamat',
                        'kota',
                        'kodepos',
                        [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, Supplier $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'supplier_id' => $model->supplier_id]);
                            }
                        ],
                    ],
                ]); ?>
            </div>

        </div>
    </div>
</div>