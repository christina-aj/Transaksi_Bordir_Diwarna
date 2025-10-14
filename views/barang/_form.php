<?php

use app\models\Barang;
use app\models\Unit;
use yii\bootstrap5\Alert as Bootstrap5Alert;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var app\models\Barang $model */
/** @var yii\widgets\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProvider */


?>

<div class="barang-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
            <!-- Tambahkan tombol Toggle -->
            <!-- Tombol Consumable dan Non Consumable -->
            <?= Html::button('Consumable', [
                'class' => 'btn btn-outline-primary',
                'id' => 'toggle-consumable-button',
            ]) ?>
            <?= Html::button('Non Consumable', [
                'class' => 'btn btn-outline-secondary',
                'id' => 'toggle-non-consumable-button',
            ]) ?>
        </div>
        <div class="card-body mx-4">

            <?php $form = ActiveForm::begin(); ?>
            <div id="barang-gridview">
                <?= GridView::widget([
                    'dataProvider' => new \yii\data\ArrayDataProvider([
                        'allModels' => $modelBarangs, // Pastikan $modelBarangs adalah array model Barang
                        'pagination' => false,
                    ]),
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'kode_barang',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]kode_barang")->textInput(['maxlength' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'nama_barang',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]nama_barang")->textInput(['maxlength' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'angka',
                            'format' => 'raw',
                            'label' => 'Jumlah',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]angka")->textInput(['maxlength' => true])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'unit_id',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                $dataPost = ArrayHelper::map(Unit::find()->asArray()->all(), 'unit_id', 'satuan');
                                return $form->field($model, "[$index]unit_id")->dropDownList($dataPost, ['prompt' => 'Pilih Satuan'])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'tipe',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                return $form->field($model, "[$index]tipe")->textInput([
                                    'class' => 'form-control tipe-field', // Menambahkan class "tipe-field"
                                    'readonly' => true,                   // Membuat field menjadi readonly
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'safety_stock',
                            'format' => 'raw',
                            'label' => 'Safety Stock',
                            'headerOptions' => ['class' => 'biaya-header'],
                            'contentOptions' => ['class' => 'biaya-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                $value = ($model->safety_stock == 0) ? '' : $model->safety_stock;
                                $inputId = "consumable-{$index}";
                                return $form->field($model, "[$index]safety_stock")->textInput([
                                    'class' => 'form-control safety-stock-field', 
                                    'maxlength' => true, 
                                    'placeholder' => 'Cth : 100',
                                    'id' => $inputId,
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'biaya_simpan_bulan',
                            'format' => 'raw',
                            'label' => 'Biaya Simpan/Bulan',
                            'headerOptions' => ['class' => 'biaya-header'],
                            'contentOptions' => ['class' => 'biaya-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                $value = ($model->biaya_simpan_bulan == 0) ? '' : $model->biaya_simpan_bulan;
                                $inputId = "consumable-{$index}";
                                return $form->field($model, "[$index]biaya_simpan_bulan")->textInput([
                                    'class' => 'form-control biaya-simpan-field', 
                                    'maxlength' => true,
                                    'placeholder' => 'Cth : 5000',
                                    'id' => $inputId,
                                    ])
                                ->label(false);
                            },
                        ],
                        [
                            'attribute' => 'jenis_barang',
                            // 'visible' => false,
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'jenis-header'],
                            'contentOptions' => ['class' => 'jenis-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                $inputId = "jenis-{$index}";
                                return $form->field($model, "[$index]jenis_barang")->textInput([
                                    'class' => 'form-control jenis-barang-field',
                                    'readonly' => true,
                                    'id' => $inputId,
                                ])->label(false);
                            },
                        ],
                        [
                            'attribute' => 'warna',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'warna-header'],
                            'contentOptions' => ['class' => 'warna-column'],
                            'value' => function ($model, $key, $index, $column) use ($form) {
                                // Mengatur ID dinamis berdasarkan indeks baris
                                $inputId = "consumable-{$index}"; // ID unik menggunakan indeks baris
                                return $form->field($model, "[$index]warna")->textInput([
                                    'maxlength' => true,
                                    'id' => $inputId, // Mengatur ID untuk setiap field
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
                            ], // Tambahkan kelas untuk gaya CSS khusus
                        ],
                    ],
                ]); ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Back', 'index', ['class' => 'btn btn-secondary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


</div>

<?php
$dataSatuan = ArrayHelper::map(Unit::find()->asArray()->all(), 'unit_id', 'satuan');

// Create options HTML
$optionsHtml = '';
foreach ($dataSatuan as $unitId => $satuan) {
    $optionsHtml .= "<option value=\"{$unitId}\">{$satuan}</option>";
}

$js = <<<JS
    // Fungsi untuk menampilkan field "warna" dan header ketika tombol Consumable ditekan
    $('#toggle-consumable-button').on('click', function() {
        $('[id^="consumable-"]').show();  // Menampilkan semua field warna
        $('[id^="jenis-"]').hide();  // hide jenis
        $('.warna-header').show();        // Menampilkan header kolom "warna"
        $('.warna-column').show();
        $('.biaya-header').show();        
        $('.biaya-column').show();
        $('.jenis-column').hide();
        $('.jenis-header').hide();  
        $('.tipe-field').val('Consumable');
        $('.jenis-barang-field').val(1);
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');
        $('#toggle-non-consumable-button').removeClass('btn-secondary').addClass('btn-outline-secondary');
    });

    // Fungsi untuk menyembunyikan field "warna" dan header ketika tombol Non Consumable ditekan
    $('#toggle-non-consumable-button').on('click', function() {
        $('[id^="consumable-"]').hide();  // Menyembunyikan semua field warna
        $('[id^="jenis-"]').hide();  // hide jenis
        $('.warna-header').hide();        // Menyembunyikan header kolom "warna"
        $('.warna-column').hide();        // Menyembunyikan kolom "warna" di setiap baris
        $('.biaya-header').hide();        
        $('.biaya-column').hide();
        $('.jenis-column').hide();
        $('.jenis-header').hide();        
        $('.safety-stock-field').val(0);
        $('.biaya-simpan-field').val(0);
        $('.tipe-field').val('Non Consumable'); 
        $('.jenis-barang-field').val(2);
        $('#toggle-consumable-button').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
    });
    // Fungsi untuk mengatur tombol di setiap baris
    function updateRowButtons() {
        var rows = $('#barang-gridview table tbody tr');
        var rowCount = rows.length;

        rows.each(function(index) {
            var isLastRow = index === rowCount - 1;
            $(this).find('.add-row').toggle(isLastRow); // Tampilkan tombol tambah hanya di baris terakhir
            
            // Sembunyikan tombol hapus jika hanya ada satu baris
            if (rowCount === 1) {
                $(this).find('.delete-row').hide();
            } else {
                $(this).find('.delete-row').show(); // Tampilkan tombol hapus di semua baris kecuali jika hanya satu
            }
        });
    }

    // Panggil fungsi updateRowButtons saat halaman dimuat
    updateRowButtons();

    // Fungsi untuk menambahkan baris baru
    $(document).on('click', '.add-row', function(e) {
        e.preventDefault();
        // Ambil jumlah baris yang ada
        var index = $('#barang-gridview table tbody tr').length;
        var newRow = `<tr>
            <td class="serial-number">\${index + 1}</td>
            <td><input type="text" name="Barang[\${index}][kode_barang]" class="form-control" maxlength="true"></td>
            <td><input type="text" name="Barang[\${index}][nama_barang]" class="form-control" maxlength="true"></td>
            <td><input type="text" name="Barang[\${index}][angka]" class="form-control" maxlength="true"></td>
            <td>
                <select name="Barang[\${index}][unit_id]" class="form-control">
                    <option value="">Pilih Satuan</option>
                    $optionsHtml <!-- Use the options generated in PHP -->
                </select>
            </td>
            <td><input type="text" name="Barang[\${index}][tipe]" class="form-control tipe-field" readonly></td>
            <td class="biaya-column"><input type="text" id="consumable-\${index}" name="Barang[\${index}][safety_stock]" class="form-control safety-stock-field" maxlength="true"></td>
            <td class="biaya-column"><input type="text" id="consumable-\${index}"  name="Barang[\${index}][biaya_simpan_bulan]" class="form-control biaya-simpan-field" maxlength="true"></td>
            <td class="jenis-column"><input type="text" id="jenis-\${index}" name="Barang[\${index}][jenis_barang]" class="form-control jenis-barang-field" readonly></td>
            <td class="warna-column"><input type="text" id="consumable-\${index}"  name="Barang[\${index}][warna]" class="form-control warna-field" maxlength="true"></td>
            <td>
                <div class="d-flex justify-content-between align-content-center align-items-center">
                    <a href="#" class="btn btn-success btn-xs pb-1 px-2 add-row" title="Tambah Baris">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a href="#" class="btn btn-danger btn-xs pb-1 px-2 delete-row" title="Hapus Baris">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </td>
        </tr>`;
        
        $('#barang-gridview table tbody').append(newRow);
        updateRowButtons(); // Perbarui tampilan tombol setelah menambah baris
    });

    // Fungsi untuk menghapus baris yang dipilih
    $(document).on('click', '.delete-row', function(e) {
        e.preventDefault();
        $(this).closest('tr').remove();

        // Update nomor urut pada kolom serial
        $('#barang-gridview table tbody tr').each(function(index) {
            $(this).find('.serial-number').text(index + 1);
        });
        updateRowButtons(); // Perbarui tampilan tombol setelah menghapus baris
    });
JS;
$this->registerJs($js);
?>

<style>
    .small-btn {
        padding: 2px 6px;
        font-size: 0.8em;
        margin-right: 2px;
    }

    /* Mengatur tata letak tombol secara horizontal */
    .action-buttons {
        display: flex;
        align-items: center;
        gap: 4px;
    }
</style>