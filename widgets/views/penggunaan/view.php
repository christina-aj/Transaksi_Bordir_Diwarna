<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */

$this->title = $model->penggunaan_id;
$this->params['breadcrumbs'][] = ['label' => 'Penggunaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pc-content">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'tanggal_digunakan',
            'penggunaan_id',
            [
                'attribute' => 'tanggal_digunakan',
                'format' => ['date', 'php:d-M-Y'], // Mengubah format menjadi dd-mm-yyyy
            ],
            'barang_id',
            'jumlah_digunakan',
            'catatan',
        ],
    ]) ?>
    <div class="mb-4">
        <?= Html::a('Update', ['update', 'penggunaan_id' => $model->penggunaan_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'penggunaan_id' => $model->penggunaan_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['penggunaan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

</div>