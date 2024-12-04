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
    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
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
        ],
    ]) ?>
    <p>
        <?= Html::a('Update', ['update', 'shift_id' => $model->shift_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'shift_id' => $model->shift_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['shift/index'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <h2>Pekerjaan</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Tanggal Kerja</th>
                <th>Nama Pekerjaan</th>
                <th>Barang</th>
                <th>Kuantitas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->laporanProduksiList as $laporan): ?>
                <tr>
                    <td><?= Html::encode($laporan->shift->nama_operator) ?> (<?= $laporan->shift->shift == '1' ? 'Pagi' : 'Sore' ?>)</td>
                    <td><?= Yii::$app->formatter->asDate($laporan->tanggal_kerja, 'php:d-m-Y') ?></td>
                    <td><?= Html::encode($laporan->nama_kerjaan) ?></td>
                    <td><?= Html::encode($laporan->nama_barang) ?></td>
                    <td><?= Html::encode($laporan->kuantitas) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
