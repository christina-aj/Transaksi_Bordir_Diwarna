<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $model */

$this->title = $model->pemesanan_id;
$this->params['breadcrumbs'][] = ['label' => 'Pemesanans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'pemesanan_id',
            'user_id',
            'tanggal' => [
                'attribute' => 'tanggal',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format menjadi dd-mm-yyyy
            ],
            'total_item',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div className="form-group">
        <?= Html::a('Update', ['update', 'pemesanan_id' => $model->pemesanan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'pemesanan_id' => $model->pemesanan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['pemesanan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>