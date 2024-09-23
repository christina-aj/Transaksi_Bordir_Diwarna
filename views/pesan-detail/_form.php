<?php

use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PesanDetail $model */
/** @var yii\widgets\ActiveForm $form */
/** @var int $pemesananId */

$data = \app\models\Barang::find()->select(['barang_id'])->column();
?>

<div class="pesan-detail-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= Html::activeHiddenInput($model, 'pemesanan_id', ['value' => Yii::$app->session->get('temporaryOrderId')]) ?>
    <?= $form->field($model, 'pemesanan_id')->textInput(['readonly' => true, 'value' => Yii::$app->session->get('temporaryOrderId')]) ?>

    <?= $form->field($model, 'barang_id')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Cari Nama Barang...'],
        'pluginOptions' => ['highlight' => true],
        'scrollable' => true,
        'dataset' => [
            [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',  // Display the formatted value
                'templates' => [
                    'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                    'suggestion' => new \yii\web\JsExpression('function(data) {
                        return "<div>" + data.barang_id +  " - " + data.kode_barang + " - " + data.nama_barang + " - " + data.angka + " " + data.satuan + " - " + data.warna + "</div>";
                    }'),
                ],
                'remote' => [
                    'url' => Url::to(['pesan-detail/search']) . '?q=%QUERY',
                    'wildcard' => '%QUERY',
                ],
            ]
        ],
        'pluginEvents' => [
            "typeahead:select" => new \yii\web\JsExpression('function(event, suggestion) {
                // Assign barang_id directly to the input field
                $("#pesandetail-barang_id").val(suggestion.id);
            }')
        ]
    ]); ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'qty_terima')->textInput() ?>

    <?= $form->field($model, 'catatan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'langsung_pakai')->checkbox(['label' => 'Langsung Pakai'], true); ?>
    <?= $form->field($model, 'is_correct')->checkbox(['label' => 'Barang Sesuai'], true); ?>

    <!-- <?= $form->field($model, 'created_at')->textInput() ?> -->

    <!-- <?= $form->field($model, 'update_at')->textInput() ?> -->

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pesan-detail/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>