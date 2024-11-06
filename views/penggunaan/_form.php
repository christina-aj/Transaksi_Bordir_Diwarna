<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Barang;
use app\models\Stock;
use app\models\Unit;
use app\models\User;
use kartik\typeahead\Typeahead;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\Penggunaan[] $models */ // Mengubah variabel untuk mewakili array model
/** @var yii\widgets\ActiveForm $form */
?>

<div class="penggunaan-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <?php $form = ActiveForm::begin(); ?>
            <div id="penggunaan-gridview">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $models, // Pastikan $models adalah array model Penggunaan
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'barang_id',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'warna-header'],
                            'contentOptions' => ['class' => 'warna-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]barang_id")->textInput(['maxlength' => true, 'readonly' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'kode_barang',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]kode_barang")->textInput(['maxlength' => true, 'readonly' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'nama_barang',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]nama_barang")->widget(Typeahead::classname(), [
                                    'options' => ['placeholder' => 'Ketik nama barang...'],
                                    'pluginOptions' => ['highlight' => true],
                                    'scrollable' => true,
                                    'dataset' => [
                                        [
                                            'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                            'display' => 'nama_barang',
                                            'templates' => [
                                                'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                                                'suggestion' => new \yii\web\JsExpression('function(data) {
                                                    return "<div>" + data.kode_barang + " - " + data.nama_barang + " (Stock: " + data.quantity_akhir + ")</div>";
                                                }')
                                            ],
                                            'remote' => [
                                                'url' => Url::to(['barang/search']) . '?query=%QUERY',
                                                'wildcard' => '%QUERY'
                                            ]
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "typeahead:selected" => "function(event, suggestion) {
                                            $('#" . Html::getInputId($model, "[$index]barang_id") . "').val(suggestion.barang_id);
                                            $('#" . Html::getInputId($model, "[$index]kode_barang") . "').val(suggestion.kode_barang);
                                            $('#" . Html::getInputId($model, "[$index]stock") . "').val(suggestion.quantity_akhir); // Mengisi field stock
                                        }"
                                    ]
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'stock',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]stock")->textInput(['maxlength' => true, 'readonly' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'user_id',
                            'format' => 'raw',
                            'label' => 'Nama Pemakai',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                $dataPost = ArrayHelper::map(User::find()->asArray()->all(), 'user_id', 'nama_pengguna');
                                return $form->field($model, "[$index]user_id")->dropDownList($dataPost, ['prompt' => 'Pilih User'])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'jumlah_digunakan',
                            'format' => 'raw',
                            'label' => 'Jumlah',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]jumlah_digunakan")->textInput(['maxlength' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'catatan',
                            'format' => 'raw',
                            'label' => 'Catatan',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]catatan")->textInput(['maxlength' => true])->label(false);
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
                    <?= Html::a('Back', ['penggunaan/index'], ['class' => 'btn btn-secondary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$dataPengguna = ArrayHelper::map(User::find()->asArray()->all(), 'user_id', 'nama_pengguna');

// Create options HTML
$optionsHtml = '';
foreach ($dataPengguna as $userId => $nama) {
    $optionsHtml .= "<option value=\"{$userId}\">{$nama}</option>";
}

$this->registerJs("

    $('.warna-header').hide();
    $('.warna-column').hide();
    // Function to initialize typeahead
    function initializeTypeahead(selector, index) {
        $(selector).typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            name: 'nama-barang',
            display: 'nama_barang', 
            limit: 10,
            source: new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama_barang'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '" . Url::to(['barang/search']) . "?query=%QUERY',
                    wildcard: '%QUERY'
                }
            }),
            templates: {
                notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
                suggestion: function(data) {
                    return `<div>\${data.kode_barang} - \${data.nama_barang} (Stock: \${data.quantity_akhir})</div>`;
                }
            }
        }).bind('typeahead:select', function(ev, suggestion) {
            var index = $(this).attr('name').match(/\\d+/)[0];
            $('input[name=\"Penggunaan[' + index + '][barang_id]\"]').val(suggestion.barang_id);
            $('input[name=\"Penggunaan[' + index + '][kode_barang]\"]').val(suggestion.kode_barang);
            $('input[name=\"Penggunaan[' + index + '][stock]\"]').val(suggestion.quantity_akhir);
        });
    }

    // Function to update row buttons visibility
    function updateRowButtons() {
        var rows = $('#penggunaan-gridview table tbody tr');
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
        $('.nama-barang').each(function(index) {
            initializeTypeahead(this, index);
        });

        // Function to add a new row
        $(document).on('click', '.add-row', function(e) {
            e.preventDefault();
            var index = $('#penggunaan-gridview table tbody tr').length;

            var newRow = `<tr>
                <td class='serial-number'>\${index + 1}</td>
                <td class='warna-column'><input type='hidden' name='Penggunaan[\${index}][barang_id]' class='form-control warna-field' maxlength='true'></td>
                <td><input type='text' name='Penggunaan[\${index}][kode_barang]' class='form-control' maxlength='true' readonly></td>
                <td><input type='text' name='Penggunaan[\${index}][nama_barang]' class='form-control nama-barang' maxlength='true'></td>
                <td><input type='text' name='Penggunaan[\${index}][stock]' class='form-control' maxlength='true' readonly></td>
                <td>
                    <select name='Penggunaan[\${index}][user_id]' class='form-control'>
                        <option value=''>Pilih User</option>
                        $optionsHtml
                    </select>
                </td>
                <td><input type='text' name='Penggunaan[\${index}][jumlah_digunakan]' class='form-control' maxlength='true'></td>
                <td><input type='text' name='Penggunaan[\${index}][catatan]' class='form-control' maxlength='true'></td>
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

            
            $('#penggunaan-gridview table tbody').append(newRow);
            updateRowButtons();
            $('.warna-header').hide();
            $('.warna-column').hide();

            // Initialize Typeahead for the new input
            initializeTypeahead(`#penggunaan-gridview input[name='Penggunaan[\${index}][nama_barang]']`, index);
        });

        // Function to delete selected row
        $(document).on('click', '.delete-row', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            $('#penggunaan-gridview table tbody tr').each(function(index) {
                $(this).find('.serial-number').text(index + 1);
            });
            updateRowButtons();
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