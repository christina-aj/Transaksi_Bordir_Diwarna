<?php

use kartik\date\DatePicker;
use kartik\typeahead\Typeahead;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Pemesanan $modelPemesanan */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="pemesanan-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">

            <?= $form->field($modelPemesanan, 'pemesanan_id')->textInput(['readonly' => true]) ?>
            <?= $form->field($modelPemesanan, 'kode_pemesanan')->textInput(['readonly' => true]) ?>
            <?= $form->field($modelPemesanan, 'user_id')->textInput(['readonly' => true]) ?>
            <?= $form->field($modelPemesanan, 'nama_pemesan')->textInput(['readonly' => true]) ?>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
            <?= $form->field($modelPemesanan, 'tanggal')->textInput(['readonly' => true]) ?>
            <?= $form->field($modelPemesanan, 'total_item')->textInput(['readonly' => true]) ?>
            <!-- Tambahkan field lain di kolom kanan sesuai kebutuhan -->
        </div>
    </div>
    <br>

    <!-- Tabel Detail Pemesanan -->
    <h3>Detail Pemesanan</h3>

    <!-- Tabel Detail Pemesanan -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 25%;">Kode Pemesanan</th>
                <th style="width: 25%;">Barang id</th>
                <th style="width: 25%;">Nama Barang</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 15%;">Qty Terima</th>
                <th style="width: 30%;">Catatan</th>
                <th style="width: 15%;">Langsung Pakai</th>
                <th style="width: 15%;">Barang sesuai</th>
                <th style="width: 5%;">Aksi</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <?php foreach ($modelDetails as $index => $modelDetail): ?>
                <tr>
                    <td><?= $form->field($modelDetail, "[$index]pemesanan_id")->textInput(['readonly' => true])->label(false) ?></td>
                    <td><?= $form->field($modelDetail, "[$index]barang_id")->textInput(['readonly' => true])->label(false) ?></td>
                    <td><?= $form->field($modelDetail, "[$index]nama_barang")->widget(Typeahead::classname(), [
                            'options' => ['placeholder' => 'Cari Nama Barang...', 'id' => 'pesandetail-0-nama_barang'],
                            'pluginOptions' => ['highlight' => true],
                            'scrollable' => true,
                            'dataset' => [
                                [
                                    'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                    'display' => 'value',
                                    'templates' => [
                                        'notFound' => "<div class='text-danger'>Tidak ada hasil</div>",
                                        'suggestion' => new \yii\web\JsExpression('function(data) {
                        if (data.barang_id && data.kode_barang && data.nama_barang) {
                            return "<div>" + data.barang_id +  " - " + data.kode_barang + " - " + data.nama_barang + " - " + data.angka + " " + data.satuan + " - " + data.warna + "</div>";
                        } else {
                            return "<div>Barang tidak ditemukan</div>";
                        }
                    }')
                                    ],
                                    'remote' => [
                                        'url' => Url::to(['pesan-detail/search']) . '?q=%QUERY',
                                        'wildcard' => '%QUERY',
                                    ],
                                ]
                            ],
                            'pluginEvents' => [
                                "typeahead:select" => new \yii\web\JsExpression('function(event, suggestion) {
                $("#hidden-pesandetail-0-barang_id").val(suggestion.barang_id);
                $("#pesandetail-0-barang_id").val(suggestion.id);
            }')
                            ]
                        ])->label(false); ?></td>
                    <td><?= $form->field($modelDetail, "[$index]qty")->textInput()->label(false) ?></td>
                    <td><?= $form->field($modelDetail, "[$index]qty_terima")->textInput()->label(false) ?></td>
                    <td><?= $form->field($modelDetail, "[$index]catatan")->textInput()->label(false) ?></td>
                    <td class="text-center">
                        <?= $form->field($modelDetail, "[$index]langsung_pakai")->checkbox([
                            'id' => "pesandetail-{$index}-langsung_pakai",
                            'label' => null // Hapus label
                        ]) ?>
                    </td>
                    <td class="text-center">
                        <?= $form->field($modelDetail, "[$index]is_correct")->checkbox([
                            'id' => "pesandetail-{$index}-is_correct",
                            'label' => null // Hapus label
                        ]) ?>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success btn-sm add-row" title="Tambah">
                                <i class="fas fa-plus"></i> <!-- Icon tambah -->
                            </button>
                            <button type="button" class="btn btn-danger btn-sm delete-row" title="Hapus" style="display:none;">
                                <i class="fas fa-trash"></i> <!-- Icon hapus -->
                            </button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pemesanan/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


    <!-- JavaScript untuk Menambah dan Menghapus Baris -->
    <?php
    $this->registerJs("
    var rowIndex = " . count($modelDetails) . ";

    // Fungsi untuk menambah baris baru
    $(document).on('click', '.add-row', function() {
        var newRow = `
            <tr>
            
                <td><input type='text' name='PesanDetail[` + rowIndex + `][barang_id]' class='form-control' readonly></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][nama_barang]' class='form-control'></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][qty]' class='form-control'></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][qty_terima]' class='form-control'></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][catatan]' class='form-control'></td>
                <td class='text-center'><input type='checkbox' name='PesanDetail[` + rowIndex + `][langsung_pakai]' class='form-check-input'></td>
                <td class='text-center'><input type='checkbox' name='PesanDetail[` + rowIndex + `][is_correct]' class='form-check-input'></td>
                <td class='text-center'>
                    <div class='btn-group' role='group'>
                        <button type='button' class='btn btn-success btn-sm add-row' title='Tambah'>
                            <i class='fas fa-plus'></i>
                        </button>
                        <button type='button' class='btn btn-danger btn-sm delete-row' title='Hapus'>
                            <i class='fas fa-trash'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        $('#table-body').append(newRow);
        rowIndex++;
        toggleAddDeleteButtons();
    });

    // Fungsi untuk menghapus baris
    $(document).on('click', '.delete-row', function() {
        $(this).closest('tr').remove();
        toggleAddDeleteButtons();
    });

     // Fungsi untuk menampilkan/menyembunyikan tombol add dan delete
    function toggleAddDeleteButtons() {
        var rows = $('#table-body tr');
        var rowCount = rows.length;

        // Sembunyikan semua tombol delete jika hanya ada satu baris, tampilkan jika lebih dari satu
        if (rowCount > 1) {
            $('.delete-row').show(); // Tampilkan semua tombol delete
        } else {
            $('.delete-row').hide(); // Sembunyikan tombol delete jika hanya satu baris
        }

        // Sembunyikan semua tombol add kecuali pada baris terakhir
        $('.add-row').hide();
        $('#table-body tr:last-child .add-row').show(); // Tampilkan tombol add hanya pada baris terakhir
    }

    // Inisialisasi awal untuk menampilkan/menyembunyikan tombol add dan delete
    toggleAddDeleteButtons();
");
    ?>

</div>