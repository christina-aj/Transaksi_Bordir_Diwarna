<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\RiwayatPenjualan;
use yii\helpers\ArrayHelper;
use app\models\Barangproduksi;
use kartik\typeahead\Typeahead;
use yii\bootstrap5\Alert;
use yii\grid\GridView;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\RiwayatPenjualan[] $models */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="riwayat-penjualan-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(); ?>
            <div id="riwayat-penjualan-gridview">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $models,
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'barang_produksi_id',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'warna-header'],
                            'contentOptions' => ['class' => 'warna-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]barang_produksi_id")->textInput(['maxlength' => true, 'readonly' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'kode_barang_produksi',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]kode_barang_produksi")->textInput(['maxlength' => true, 'readonly' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'nama',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]nama")->widget(Typeahead::classname(), [
                                    'options' => [
                                        'placeholder' => 'Ketik nama barang produksi...',
                                        'id' => "typeahead-nama-$index"
                                    ],
                                    'pluginOptions' => [
                                        'highlight' => true
                                    ],
                                    'scrollable' => true,
                                    'dataset' => [
                                        [
                                            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                            'display' => 'nama',
                                            'templates' => [
                                                'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                                                'suggestion' => new \yii\web\JsExpression('function(data) {
                                                    return "<div>" + data.kode_barang_produksi + " - " + data.nama + "</div>";
                                                }')
                                            ],
                                            'remote' => [
                                                'url' => Url::to(['riwayat-penjualan/search']) . '?query=%QUERY&_=' . new \yii\web\JsExpression('Date.now()'),
                                                'wildcard' => '%QUERY',
                                                'cache' => false,
                                                'ttl' => 0
                                            ]
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "typeahead:selected" => "function(event, suggestion) {
                                            var barangProduksiIdField = $('#" . Html::getInputId($model, "[$index]barang_produksi_id") . "');
                                            var kodeBarangProduksiField = $('#" . Html::getInputId($model, "[$index]kode_barang_produksi") . "');
                                            
                                            barangProduksiIdField.val(suggestion.barang_produksi_id);
                                            kodeBarangProduksiField.val(suggestion.kode_barang_produksi);
                                        }"
                                    ]
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'qty_penjualan',
                            'format' => 'raw',
                            'label' => 'Qty',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]qty_penjualan")->textInput([
                                    'type' => 'number',
                                    'placeholder' => 'Jumlah',
                                    'class' => 'form-control'
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'bulan_periode',
                            'format' => 'raw',
                            'label' => 'Bulan Periode',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]bulan_periode")->textInput([
                                    'type' => 'number',
                                    'placeholder' => 'YYYYMM (contoh: 202410)',
                                    'class' => 'form-control',
                                    'maxlength' => 6,
                                    'min' => 200001,
                                    'max' => 999912
                                ])->label(false);
                            },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{actions}',
                            'buttons' => [
                                'actions' => function ($url, $model) {
                                    return Html::tag(
                                        'div',
                                        Html::a(Html::tag('i', '', ['class' => 'fas fa-plus fa-xs']), '#', [
                                            'class' => 'btn btn-success btn-xs pb-1 px-2 add-row ',
                                            'onclick' => 'return false;',
                                        ]) .
                                            Html::a(Html::tag('i', '', ['class' => 'fas fa-trash fa-xs']), '#', [
                                                'class' => 'btn btn-danger btn-xs pb-1 px-2 delete-row ',
                                                'onclick' => 'return false;',
                                            ]),
                                        ['class' => 'd-flex justify-content-between align-content-center align-items-center']
                                    );
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs("
    $(document).ready(function() {
        $('.warna-header').hide();
        $('.warna-column').hide();

        // Function to initialize Typeahead with event binding
        function initializeTypeahead(selector, index) {
            $(selector).typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'nama-barang-produksi',
                display: 'nama', 
                limit: 10,
                source: new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    remote: {
                        url: '" . Url::to(['riwayat-penjualan/search']) . "?query=%QUERY',
                        wildcard: '%QUERY'
                    }
                }),
                templates: {
                    notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
                    suggestion: function(data) {
                        return `<div>\${data.kode_barang_produksi} - \${data.nama}</div>`;
                    }
                }
            }).bind('typeahead:select', function(ev, suggestion) {
                var barangProduksiIdField = $('input[name=\"RiwayatPenjualan[' + index + '][barang_produksi_id]\"]');
                var kodeBarangProduksiField = $('input[name=\"RiwayatPenjualan[' + index + '][kode_barang_produksi]\"]');

                barangProduksiIdField.val(suggestion.barang_produksi_id);
                kodeBarangProduksiField.val(suggestion.kode_barang_produksi);
            });
        }

        // Function to update row buttons visibility
        function updateRowButtons() {
            var rows = $('#riwayat-penjualan-gridview table tbody tr');
            var rowCount = rows.length;

            rows.each(function(index) {
                var isLastRow = index === rowCount - 1;
                $(this).find('.add-row').toggle(isLastRow);
                $(this).find('.delete-row').toggle(rowCount > 1);
            });
        }

        $(document).ready(function() {
            updateRowButtons();

            // Initialize Typeahead for existing inputs
            $('.nama-barang-produksi').each(function(index) {
                initializeTypeahead(this, index);
            });

            // Function to add a new row
            $(document).on('click', '.add-row', function(e) {
                e.preventDefault();
                var index = $('#riwayat-penjualan-gridview table tbody tr').length;

                var newRow = `<tr>
                    <td class='serial-number'>\${index + 1}</td>
                    <td class='warna-column'><input type='hidden' name='RiwayatPenjualan[\${index}][barang_produksi_id]' class='form-control warna-field' maxlength='true'></td>
                    <td><input type='text' name='RiwayatPenjualan[\${index}][kode_barang_produksi]' class='form-control' maxlength='true' readonly></td>
                    <td><input type='text' name='RiwayatPenjualan[\${index}][nama]' class='form-control nama-barang-produksi' id='typeahead-nama-\${index}' maxlength='true' placeholder='Ketik nama barang produksi...'></td>
                    <td><input type='number' name='RiwayatPenjualan[\${index}][qty_penjualan]' class='form-control' placeholder='Jumlah'></td>
                    <td><input type='number' name='RiwayatPenjualan[\${index}][bulan_periode]' class='form-control' placeholder='YYYYMM'></td>
                    <td>
                        <div class='d-flex justify-content-between align-content-center align-items-center'>
                            <a href='#' class='btn btn-success btn-xs pb-1 px-2 add-row' title='Tambah Baris'>
                                <i class='fas fa-plus'></i>
                            </a>
                            <a href='#' class='btn btn-danger btn-xs pb-1 px-2 delete-row' title='Hapus Baris'>
                                <i class='fas fa-trash'></i>
                            </a>
                        </div>
                    </td>
                </tr>`;

                $('#riwayat-penjualan-gridview table tbody').append(newRow);
                updateRowButtons();
                $('.warna-header').hide();
                $('.warna-column').hide();

                // Initialize Typeahead for the new input with event binding
                initializeTypeahead(`#typeahead-nama-\${index}`, index);
            });

            // Function to delete selected row
            $(document).on('click', '.delete-row', function(e) {
                e.preventDefault();
                $(this).closest('tr').remove();
                $('#riwayat-penjualan-gridview table tbody tr').each(function(index) {
                    $(this).find('.serial-number').text(index + 1);
                });
                updateRowButtons();
            });
        });
    });
");
?>

<style>
    /* CSS untuk membuat dropdown saran scrollable */
    .tt-menu {
        max-height: 200px;
        /* Tinggi maksimal dropdown */
        overflow-y: auto;
        /* Aktifkan scroll vertikal */
    }
</style>