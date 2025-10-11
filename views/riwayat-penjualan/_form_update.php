<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualan $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-penjualan-form">
    <div class="card table-card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'barang_produksi_id')->textInput(['readonly' => true]) ?>

            <?= $form->field($model, 'kode_barang_produksi')->textInput(['readonly' => true]) ?>

            <?= $form->field($model, 'nama')->textInput([
                'class' => 'form-control typeahead-nama',
                'placeholder' => 'Ketik nama barang produksi...',
                'autocomplete' => 'off'
            ]) ?>

            <?= $form->field($model, 'qty_penjualan')->textInput([
                'type' => 'number',
                'placeholder' => 'Jumlah'
            ]) ?>

            <?= $form->field($model, 'bulan_periode')->textInput([
                'type' => 'number',
                'placeholder' => 'YYYYMM (contoh: 202410)',
                'maxlength' => 6,
                'min' => 200001,
                'max' => 999912
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$searchUrl = Url::to(['riwayat-penjualan/search']);

$this->registerJs("
$(document).ready(function() {
    // Initialize Bloodhound
    var bhBarangProduksi = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '$searchUrl?query=%QUERY',
            wildcard: '%QUERY'
        }
    });

    // Initialize Typeahead
    $('.typeahead-nama').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'barang-produksi',
        display: 'nama',
        limit: 10,
        source: bhBarangProduksi,
        templates: {
            notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
            suggestion: function(data) {
                return '<div>' + data.kode_barang_produksi + ' - ' + data.nama + '</div>';
            }
        }
    }).bind('typeahead:select', function(ev, suggestion) {
        // Update fields when selecting suggestion
        $('#riwayatpenjualan-barang_produksi_id').val(suggestion.barang_produksi_id);
        $('#riwayatpenjualan-kode_barang_produksi').val(suggestion.kode_barang_produksi);
    });
});
");
?>

<style>
    /* CSS untuk membuat dropdown saran scrollable */
    .tt-menu {
        max-height: 200px;
        overflow-y: auto;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .tt-suggestion {
        padding: 8px 12px;
        cursor: pointer;
    }
    
    .tt-suggestion:hover {
        background-color: #f5f5f5;
    }
</style>