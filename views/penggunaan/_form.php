<?php

use kartik\date\DatePicker;
use kartik\typeahead\Typeahead;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;


/** @var yii\web\View $this */
/** @var app\models\Penggunaan $model */
/** @var yii\widgets\ActiveForm $form */
echo Dialog::widget();
// 🔥 DEBUG: Cek permintaan_id
// $permintaanId = Yii::$app->request->get('permintaan_id');
// if (!empty($permintaanId)) {
//     echo "<div class='alert alert-info'>DEBUG: permintaan_id = {$permintaanId}</div>";
// } else {
//     echo "<div class='alert alert-warning'>DEBUG: permintaan_id KOSONG</div>";
// }
?>

<div class="penggunaan-form">
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3 ">
            <!-- Baris pertama: Kode Pembelian, Kode Penggunaan, Nama Pemesan -->
            <div class="col-md-3">
                <div><strong>Kode Penggunaan:</strong> <?= $modelPenggunaan ? $modelPenggunaan->getFormattedGunaId() : 'Kode Penggunaan Tidak Tersedia' ?></div>
                <div><strong>Nama Pengguna:</strong> <?= $modelPenggunaan->user->nama_pengguna ?? 'Nama Pemesan Tidak Tersedia' ?></div>
            </div>

            <!-- Baris kedua: Tanggal Penggunaan, Total Item, Total Biaya, Aksi -->
            <div class="col-md-3">
                <div><strong>Total Item:</strong> <?= $modelPenggunaan->total_item_penggunaan ?? '-' ?></div>
                <div><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($modelPenggunaan->tanggal ?? 'Tanggal Tidak Tersedia') ?></div>
            </div>

            <!-- TAMBAHAN INFO PERMINTAAN -->
            <?php if (!empty($modelPenggunaan->permintaan_id)): ?>
            <div class="col-md-3">
                <div><strong>Dari Permintaan:</strong> 
                    <?= Html::a(
                        $modelPenggunaan->permintaanPelanggan->generateKodePermintaan(), 
                        ['permintaan-pelanggan/view', 'permintaan_id' => $modelPenggunaan->permintaan_id],
                        ['class' => 'btn btn-sm btn-info']
                    ) ?>
                </div>
                <div><strong>Status Permintaan:</strong> <?= $modelPenggunaan->permintaanPelanggan->getStatusLabel() ?></div>
            </div>
            <?php endif; ?>

        </div>
        <br>
        <hr>
        <div class="card-body mx-4">
            <!-- Tabel Detail Penggunaan -->
            <?php $form = ActiveForm::begin([
                'action' => ['update', 'penggunaan_id' => $modelPenggunaan->penggunaan_id],
                'method' => 'post',
            ]); ?>
            <h3>Detail Penggunaan</h3>

            <!-- Tabel Detail Penggunaan -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th style="width: 25%;">Kode Penggunaan</th> -->
                        <th style="width: 25%;" class="barang-header">Barang id</th>
                        <th style="width: 25%;">Kode Barang</th>
                        <th style="width: 25%;">Nama Barang</th>
                        <th style="width: 15%;">Jumlah (gram)</th>
                        <!-- <th style="width: 15%;" class="barang-header">jumlah_digunakan Terima</th> -->
                        <th style="width: 30%;">Catatan</th>
                        <th style="width: 15%;" class="barang-header">Area Pengambilan</th>
                        <th style="width: 5%;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <?php foreach ($modelDetails as $index => $modelDetail): ?>
                        <tr>
                            <?= Html::activeHiddenInput($modelDetail, "[$index]penggunaan_id", ['value' => $modelPenggunaan->penggunaan_id]) ?>
                            <td class="barang-column"><?= $form->field($modelDetail, "[$index]barang_id")->textInput([
                                                            'readonly' => true,
                                                        ])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]kode_barang")->textInput(['readonly' => true])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]nama_barang")->widget(Typeahead::classname(), [
                                    'options' => [
                                        'placeholder' => 'Cari Nama Barang...',
                                        'id' => "penggunaandetail-{$index}-nama_barang",  // Menggunakan ID dinamis
                                        'value' => $modelDetail->nama_barang,
                                    ],
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
                                                        return "<div>" + data.kode_barang + " - " + data.nama_barang + "</div>";
                                                    } else {
                                                        return "<div>Barang tidak ditemukan</div>";
                                                    }
                                                }')
                                            ],
                                            'remote' => [
                                                'url' => Url::to(['penggunaan-detail/search']) . '?q=%QUERY',
                                                'wildcard' => '%QUERY',
                                            ],
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "typeahead:select" => new \yii\web\JsExpression("function(event, suggestion) {
                                            $('#penggunaandetail-{$index}-barang_id').val(suggestion.barang_id);  // ID dinamis
                                            $('#penggunaandetail-{$index}-kode_barang').val(suggestion.kode_barang);
                                        }")
                                    ]
                                ])->label(false); ?></td>
                            <td><?= $form->field($modelDetail, "[$index]jumlah_digunakan")->textInput()->label(false) ?></td>
                            <td class="barang-column"><?= $form->field($modelDetail, "[$index]area_gudang")->textInput(['readonly' => true])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]catatan")->textInput()->label(false) ?></td>
                           
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm add-row" id="add-rows" title="Tambah">
                                        <i class="fas fa-plus"></i> <!-- Icon tambah -->
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-row" id="delete-rows" data-id="<?= $modelDetail->gunadetail_id ?>" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Hidden input untuk menyimpan ID detail yang akan dihapus -->
            <?= Html::hiddenInput('deleteRows', '', ['id' => 'deleteRows']) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' => 'saveButtons']) ?>

            </div>

            <?php ActiveForm::end(); ?>
            <?php if ($modelDetail->isNewRecord): ?>

                <!-- Mode Create: Tombol Back berfungsi sebagai tombol Cancel -->
                <?= Html::a('Cancel', [
                    'cancel',
                    'penggunaan_id' => $modelPenggunaan->penggunaan_id,

                ], [
                    'id' => 'cancelButtons',
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Apakah Anda yakin ingin membatalkan Penggunaan ini?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php else: ?>
                <!-- Mode Update: Tombol Back berfungsi untuk kembali ke halaman view -->
                <?= Html::a('Back', ['view', 'penggunaan_id' => $modelPenggunaan->penggunaan_id], [
                    'class' => 'btn btn-secondary',
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript untuk Menambah dan Menghapus Baris -->
<?php
// Dapatkan nilai `penggunaan_id` dari model
$PenggunaanId = $modelPenggunaan->penggunaan_id;
$isNewRecord = $modelDetail->isNewRecord;

// TAMBAHAN UNTUK AUTO-FILL BOM
$permintaanId = Yii::$app->request->get('permintaan_id');
$urlGetBom = !empty($permintaanId) ? Url::to(['penggunaan/get-bom-data', 'permintaan_id' => $permintaanId]) : '';

$this->registerJs("
    var rowIndex = " . count($modelDetails) . ";
    var PenggunaanId = " . json_encode($PenggunaanId) . ";
    $('.barang-header').hide();
    $('.barang-column').hide();

    // Fungsi untuk menambah baris baru
    $(document).on('click', '.add-row', function() {
        var newRow = `
            <tr>
                <input type='hidden' name='PenggunaanDetail[` + rowIndex + `][penggunaan_id]' value='` + PenggunaanId + `'>
                <td class='barang-column'><input type='text' name='PenggunaanDetail[` + rowIndex + `][barang_id]' id='penggunaandetail-` + rowIndex + `-barang_id' class='form-control' readonly></td>
                <td><input type='text' name='PenggunaanDetail[` + rowIndex + `][kode_barang]' id='penggunaandetail-` + rowIndex + `-kode_barang' class='form-control' readonly></td>
                <td><input type='text' name='PenggunaanDetail[` + rowIndex + `][nama_barang]' id='penggunaandetail-` + rowIndex + `-nama_barang' class='form-control typeahead-input' data-index='` + rowIndex + `' placeholder='Cari Nama Barang...'></td>
                <td><input type='number' name='PenggunaanDetail[` + rowIndex + `][jumlah_digunakan]' class='form-control' step='0.01' min='0'></td>
                <td><input type='text' name='PenggunaanDetail[` + rowIndex + `][catatan]' class='form-control'></td>
                <td class='barang-column'><input type='text' name='PenggunaanDetail[` + rowIndex + `][area_gudang]' class='form-control' readonly></td>
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
        initializeTypeahead('#penggunaandetail-' + rowIndex + '-nama_barang', rowIndex);
        $('.barang-header').hide();
        $('.barang-column').hide();
        rowIndex++;
        toggleAddDeleteButtons();
    });

    var formChanged = true;
    var isNewRecord = " . json_encode($isNewRecord) . ";

    $(document).ready(function() {
        $('button, a').on('click', function(e) {
            if (formChanged && isNewRecord && !$(e.currentTarget).is('#cancelButtons, #saveButtons, .cancel-class, .save-class, #add-rows')) {
                console.log('Tombol yang diklik: ' + $(e.currentTarget).attr('id'));
                e.preventDefault();
                krajeeDialog.alert('Selesaikan form dahulu atau cancel form Penggunaan').setDefaults({
                    'backdrop': 'static',
                    'zIndex': 9999
                });
            }
        });
    });

    // Fungsi untuk menghapus baris
    $(document).on('click', '.delete-row', function() {
        var id = $(this).data('id');
        if (id) {
            var deleteRows = $('#deleteRows').val() ? JSON.parse($('#deleteRows').val()) : [];
            deleteRows.push(id);
            $('#deleteRows').val(JSON.stringify(deleteRows));
        }
        $(this).closest('tr').remove();
        toggleAddDeleteButtons();
    });

    // Fungsi untuk menginisialisasi typeahead pada input baru
    function initializeTypeahead(selector, index) {
        $(selector).typeahead({
            highlight: true,
            minLength: 1
        },
        {
            name: 'barang',
            display: 'value',
            limit: 10,
            source: new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('nama_barang'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '" . Url::to(['penggunaan-detail/search']) . "?q=%QUERY',
                    wildcard: '%QUERY'
                }
            }),
            templates: {
                notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
                suggestion: function(data) {
                    return `<div>\${data.kode_barang} - \${data.nama_barang}</div>`;
                }
            }
        }).bind('typeahead:select', function(ev, suggestion) {
            $(`#penggunaandetail-\${index}-barang_id`).val(suggestion.barang_id);
            $(`#penggunaandetail-\${index}-kode_barang`).val(suggestion.kode_barang);
        });

        $(selector).attr('placeholder', 'Cari Nama Barang...');
    }

    // Fungsi untuk menampilkan/menyembunyikan tombol add dan delete
    function toggleAddDeleteButtons() {
        var rows = $('#table-body tr');
        var rowCount = rows.length;

        if (rowCount > 1) {
            $('.delete-row').show();
        } else {
            $('.delete-row').hide();
        }

        $('.add-row').hide();
        $('#table-body tr:last-child .add-row').show();
    }

    toggleAddDeleteButtons();

    // AUTO-FILL BOM - DENGAN KONVERSI KG KE GRAM 
    " . (!empty($permintaanId) ? "
    \$(document).ready(function() {
        setTimeout(function() {
            \$.ajax({
                url: '" . $urlGetBom . "',
                type: 'GET',
                success: function(res) {
                    if (res.success && res.data.length > 0) {
                        \$('#table-body').empty();
                        rowIndex = 0;
                        \$.each(res.data, function(i, b) {
                            // KONVERSI KG KE GRAM (kali 1000)
                            var qtyGram = parseFloat(b.qty) * 1000;
                            
                            var row = '<tr>' +
                                '<input type=\"hidden\" name=\"PenggunaanDetail['+i+'][penggunaan_id]\" value=\"'+PenggunaanId+'\">' +
                                '<td class=\"barang-column\"><input type=\"text\" name=\"PenggunaanDetail['+i+'][barang_id]\" id=\"penggunaandetail-'+i+'-barang_id\" class=\"form-control\" value=\"'+b.barang_id+'\" readonly></td>' +
                                '<td><input type=\"text\" name=\"PenggunaanDetail['+i+'][kode_barang]\" id=\"penggunaandetail-'+i+'-kode_barang\" class=\"form-control\" value=\"'+b.kode_barang+'\" readonly></td>' +
                                '<td><input type=\"text\" name=\"PenggunaanDetail['+i+'][nama_barang]\" id=\"penggunaandetail-'+i+'-nama_barang\" class=\"form-control typeahead-input\" data-index=\"'+i+'\" value=\"'+b.nama_barang+'\" placeholder=\"Cari Nama Barang...\"></td>' +
                                '<td><input type=\"number\" name=\"PenggunaanDetail['+i+'][jumlah_digunakan]\" class=\"form-control\" value=\"'+qtyGram+'\" min=\"0\" step=\"0.01\" required></td>' +
                                '<td><input type=\"text\" name=\"PenggunaanDetail['+i+'][catatan]\" class=\"form-control\" value=\"'+b.catatan+'\"></td>' +
                                '<td class=\"barang-column\"><input type=\"text\" name=\"PenggunaanDetail['+i+'][area_gudang]\" class=\"form-control\" readonly></td>' +
                                '<td class=\"text-center\"><div class=\"btn-group\"><button type=\"button\" class=\"btn btn-success btn-sm add-row\"><i class=\"fas fa-plus\"></i></button><button type=\"button\" class=\"btn btn-danger btn-sm delete-row\"><i class=\"fas fa-trash\"></i></button></div></td>' +
                                '</tr>';
                            \$('#table-body').append(row);
                            initializeTypeahead('#penggunaandetail-'+i+'-nama_barang', i);
                            rowIndex++;
                        });
                        \$('.barang-header').hide();
                        \$('.barang-column').hide();
                        toggleAddDeleteButtons();
                        alert('Form akan diisi dengan '+res.data.length+' item dari BOM ('+res.kode_permintaan+'). Silakan review dan Save.');
                    } else {
                        alert(res.message || 'Tidak ada BOM');
                    }
                },
                error: function() {
                    alert('Gagal memuat data BOM');
                }
            });
        }, 500);
    });
    " : "") . "
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

    /* Pastikan modal muncul di atas elemen lain */
    /* Pastikan modal berada di atas overlay */
    .modal {
        z-index: 1051;
        /* Atur sesuai kebutuhan */
    }

    .modal-backdrop {
        z-index: 1050;
        /* Pastikan backdrop memiliki z-index lebih rendah */
    }
</style>