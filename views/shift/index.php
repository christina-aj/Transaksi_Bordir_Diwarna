<?php

use app\models\Shift;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Shiftsearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Shifts';
$this->params['breadcrumbs'][] = $this->title;
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

<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Create Shift', ['create'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'user_id',
        [
            'attribute' => 'tanggal',
            'value' => function($model) {
                return Yii::$app->formatter->asDate($model->tanggal, 'php:d-m-Y');
            },
        ],
        'shift',
        'waktu_kerja',
        'nama_operator', 
        'mulai_istirahat',
        'selesai_istirahat',
        'kendala:ntext',
        'ganti_benang',
        'ganti_kain',
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, Shift $model, $key, $index, $column) {
                return Url::toRoute([$action, 'shift_id' => $model->shift_id]);
            }
        ],
    ],
]); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const cells = document.querySelectorAll('td, th');
    cells.forEach(cell => {
        if (cell.textContent.trim() === '(not set)') {
            cell.textContent = 'kosong';
        }
    });
});
</script>

</div>
