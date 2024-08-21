<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Shift $model */

$this->title = $model->shift_id;
$this->params['breadcrumbs'][] = ['label' => 'Shifts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'shift_id' => $model->shift_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'shift_id' => $model->shift_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'shift_id',
            'user_id',
            'tanggal',
            'shift',
            'waktu_kerja',
            'nama_operator',
            'mulai_istirahat',
            'selesai_istirahat',
            'kendala:ntext',
            'ganti_benang',
            'ganti_kain',
        ],
    ]) ?>

    <?= Html::a('Back', ['shift/index'], ['class' => 'btn btn-secondary']) ?>

</div>
