<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\EoqRopHistory $model */

$this->title = $model->eoq_rop_history_id;
$this->params['breadcrumbs'][] = ['label' => 'Eoq Rop Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="eoq-rop-history-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'eoq_rop_history_id' => $model->eoq_rop_history_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'eoq_rop_history_id' => $model->eoq_rop_history_id], [
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
            'eoq_rop_history_id',
            'barang_id',
            'biaya_pesan_snapshot',
            'biaya_simpan_snapshot',
            'safety_stock_snapshot',
            'lead_time_snapshot:datetime',
            'demand_snapshot',
            'total_biaya_perediaan',
            'hasil_eoq',
            'hasil_rop',
            'periode',
            'created_at',
        ],
    ]) ?>

</div>
