<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="pc-content">


    <h1>Informasi Akun Pengguna</h1>
    <!-- <h1> User <?= Html::encode($this->title) ?></h1> -->
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_id',
            'id_role',
            'nama_pengguna',
            'kata_sandi',
            'email:email',
            'authKey',
            'dibuat_pada:datetime',
            'diperbarui_pada:datetime',
        ],
    ]) ?>
    <div>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Back', ['user/index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>