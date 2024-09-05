<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var array $aggregatedData */

$this->title = 'Laporan Agregat Bulanan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pc-content">

        <h1><?= Html::encode($this->title) ?></h1>

        <?= GridView::widget([
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $aggregatedData,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                
                [
                    'attribute' => 'year',
                    'label' => 'Tahun',
                ],
                [
                    'attribute' => 'month',
                    'label' => 'Bulan',
                ],
                [
                    'attribute' => 'nama_kerjaan',
                    'label' => 'Job Name',
                ],
                [
                    'attribute' => 'total_kuantitas',
                    'label' => 'Qty',
                    'format' => ['integer'],
                ],
            ],
        ]); ?>
</div>