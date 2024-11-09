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
    <div class="card table-card">
        <div class="card-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="row mx-3 ">
            <!-- Baris pertama: Kode Pembelian, Kode Pemesanan, Nama Pemesan -->
            <div class="col-md-3">
                <div><strong>Kode Pemesanan:</strong> <?= $modelPemesanan ? $modelPemesanan->getFormattedOrderId() : 'Kode Pemesanan Tidak Tersedia' ?></div>
                <div><strong>Nama Pemesan:</strong> <?= $modelPemesanan->user->nama_pengguna ?? 'Nama Pemesan Tidak Tersedia' ?></div>
            </div>

            <!-- Baris kedua: Tanggal Pemesanan, Total Item, Total Biaya, Aksi -->
            <div class="col-md-3">
                <div><strong>Total Item:</strong> <?= $modelPemesanan->total_item ?? '-' ?></div>
                <div><strong>Tanggal:</strong> <?= Yii::$app->formatter->asDate($modelPemesanan->tanggal ?? 'Tanggal Tidak Tersedia') ?></div>

            </div>
        </div>
        <br>
        <hr>
        <div class="card-body mx-4">
            <!-- Tabel Detail Pemesanan -->
            <?php $form = ActiveForm::begin(); ?>
            <h3>Detail Pemesanan</h3>

            <!-- Tabel Detail Pemesanan -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th style="width: 25%;">Kode Pemesanan</th> -->
                        <th style="width: 25%;" class="barang-header">Barang id</th>
                        <th style="width: 25%;">Kode Barang</th>
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
                            <?= Html::activeHiddenInput($modelDetail, "[$index]pemesanan_id", ['value' => $modelPemesanan->pemesanan_id]) ?>
                            <td class="barang-column"><?= $form->field($modelDetail, "[$index]barang_id")->textInput([
                                                            'readonly' => true,
                                                        ])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]kode_barang")->textInput(['readonly' => true])->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]nama_barang")->widget(Typeahead::classname(), [
                                    'options' => [
                                        'placeholder' => 'Cari Nama Barang...',
                                        'id' => "pesandetail-{$index}-nama_barang",  // Menggunakan ID dinamis
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
                                                'url' => Url::to(['pesan-detail/search']) . '?q=%QUERY',
                                                'wildcard' => '%QUERY',
                                            ],
                                        ]
                                    ],
                                    'pluginEvents' => [
                                        "typeahead:select" => new \yii\web\JsExpression("function(event, suggestion) {
                                            $('#pesandetail-{$index}-barang_id').val(suggestion.barang_id);  // ID dinamis
                                            $('#pesandetail-{$index}-kode_barang').val(suggestion.kode_barang);
                                        }")
                                    ]
                                ])->label(false); ?></td>
                            <td><?= $form->field($modelDetail, "[$index]qty")->textInput()->label(false) ?></td>
                            <td><?= $form->field($modelDetail, "[$index]qty_terima")->textInput(['readonly' => true])->label(false) ?></td>
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
                                    'label' => null, // Hapus label
                                    'disabled' => true,
                                    'value' => 0,
                                ]) ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-success btn-sm add-row" title="Tambah">
                                        <i class="fas fa-plus"></i> <!-- Icon tambah -->
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-row" data-id="<?= $modelDetail->pesandetail_id ?>" title="Hapus">
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
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

            </div>

            <?php ActiveForm::end(); ?>
            <?php if ($modelDetail->isNewRecord): ?>

                <!-- Mode Create: Tombol Back berfungsi sebagai tombol Cancel -->
                <?= Html::a('Cancel', ['cancel', 'pemesanan_id' => $modelPemesanan->pemesanan_id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Apakah Anda yakin ingin membatalkan pemesanan ini?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php else: ?>
                <!-- Mode Update: Tombol Back berfungsi untuk kembali ke halaman view -->
                <?= Html::a('Back', ['view', 'pemesanan_id' => $modelPemesanan->pemesanan_id], [
                    'class' => 'btn btn-secondary',
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript untuk Menambah dan Menghapus Baris -->
<?php
// Dapatkan nilai `pemesanan_id` dari model
$pemesananId = $modelPemesanan->pemesanan_id;

$this->registerJs("

    

    var rowIndex = " . count($modelDetails) . ";
    var pemesananId = " . json_encode($pemesananId) . "; // Menyimpan nilai pemesanan_id dari server
    $('.barang-header').hide();
    $('.barang-column').hide();
    // Fungsi untuk menambah baris baru
    $(document).on('click', '.add-row', function() {
        var newRow = `
            <tr>
                <input type='hidden' name='PesanDetail[` + rowIndex + `][pemesanan_id]' value='` + pemesananId + `' class='form-control' readonly>
                <td class='barang-column'><input type='text' name='PesanDetail[` + rowIndex + `][barang_id]' id='pesandetail-` + rowIndex + `-barang_id' class='form-control' readonly></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][kode_barang]' id='pesandetail-` + rowIndex + `-kode_barang' class='form-control' readonly></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][nama_barang]' id='pesandetail-` + rowIndex + `-nama_barang' class='form-control typeahead-input' data-index='` + rowIndex + `'></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][qty]' class='form-control'></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][qty_terima]' class='form-control' readonly></td>
                <td><input type='text' name='PesanDetail[` + rowIndex + `][catatan]' class='form-control'></td>
                <td class='text-center'><input type='checkbox' name='PesanDetail[` + rowIndex + `][langsung_pakai]' value='1' class='form-check-input langsung_pakai'></td>
                <td class='text-center'><input type='checkbox' name='PesanDetail[` + rowIndex + `][is_correct]' value='1' class='form-check-input is_correct' disabled></td>
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
        initializeTypeahead(`#pesandetail-` + rowIndex + `-nama_barang`, rowIndex);
        $('.barang-header').hide();
        $('.barang-column').hide();
        rowIndex++;
        toggleAddDeleteButtons();
    });

    // Fungsi untuk menghapus baris
    $(document).on('click', '.delete-row', function() {
        var id = $(this).data('id');
        if (id) {
            // Tambahkan ID detail ke array deleteRows
            var deleteRows = $('#deleteRows').val() ? JSON.parse($('#deleteRows').val()) : [];
            deleteRows.push(id);
            $('#deleteRows').val(JSON.stringify(deleteRows));
        }
        $(this).closest('tr').remove(); // Hapus baris dari tampilan form
        toggleAddDeleteButtons();
    });


    // Fungsi untuk menambahkan input hidden dengan nilai 0 jika checkbox tidak dicentang
    $('form').on('submit', function() {
        $('.langsung_pakai, .is_correct').each(function() {
            var checkbox = $(this);
            if (!checkbox.is(':checked')) {
                checkbox.after('<input type=\"hidden\" name=\"' + checkbox.attr('name') + '\" value=\"0\">');
            }
        });
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
                    url: '" . Url::to(['pesan-detail/search']) . "?q=%QUERY',
                    wildcard: '%QUERY'
                }
            }),
            templates: {
                notFound: '<div class=\"text-danger\">Tidak ada hasil</div>',
                suggestion: function(data) {
                    return `<div>\${data . kode_barang} - \${data . nama_barang}</div>`;
                }
            }
        }).bind('typeahead:select', function(ev, suggestion) {
            $(`#pesandetail-\${index}-barang_id`).val(suggestion.barang_id);
            $(`#pesandetail-\${index}-kode_barang`).val(suggestion.kode_barang);
        });
    }

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
<style>
    /* CSS untuk membuat dropdown saran scrollable */
    .tt-menu {
        max-height: 200px;
        /* Tinggi maksimal dropdown */
        overflow-y: auto;
        /* Aktifkan scroll vertikal */
    }
</style>