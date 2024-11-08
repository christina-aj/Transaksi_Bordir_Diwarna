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

$pemesananId = Yii::$app->session->get('temporaryOrderId'); // Ambil pemesanan_id dari session
Yii::debug("Pemesanan ID yang digunakan: " . $pemesananId, __METHOD__);
$isCreate = Yii::$app->controller->action->id === 'create';
if ($isCreate) {
    $pemesananId = Yii::$app->session->get('temporaryOrderId');
} else {
    $pemesananId = $modelDetail[0]->pemesanan_id;
}
$formattedOrderId = $modelDetail[0]->getFormattedOrderIdProperty($pemesananId);
?>

<div class="pesan-detail-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">

            <!-- <?= $form->field($pemesanan, 'pemesanan_id')->textInput(['readonly' => true]) ?> -->
            <?= $form->field($pemesanan, 'kode_pemesanan')->textInput(['readonly' => true, 'value' => $pemesanan->getFormattedOrderId()]) ?>
            <!-- <?= $form->field($pemesanan, 'user_id')->textInput(['readonly' => true]) ?> -->
            <?= $form->field($pemesanan->user, 'nama_pemesan')->textInput(['readonly' => true, 'value' => $pemesanan->user ? $pemesanan->user->nama_pengguna : '']) ?>
        </div>

        <!-- Kolom Kanan -->
        <div class="col-md-6">
            <?= $form->field($pemesanan, 'tanggal')->textInput(['readonly' => true]) ?>
            <?= $form->field($pemesanan, 'total_item')->textInput(['readonly' => true]) ?>
            <!-- Tambahkan field lain di kolom kanan sesuai kebutuhan -->
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form', 'method' => 'post']); ?>

    <br>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Pemesanan</th>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Catatan</th>
                <th>Langsung Pakai</th>
                <?php if (!$isCreate): ?>
                    <th>Qty Terima</th>
                    <th>Barang Sesuai</th>
                <?php endif; ?>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($isCreate): ?>
                <tr id="pemesanan-item-0">
                    <td>
                        <?= Html::activeHiddenInput($modelDetail[0], '[0]pemesanan_id', ['value' => $pemesananId]) ?>
                        <?= $form->field($modelDetail[0], '[0]kode_pemesanan')->textInput([
                            'value' => $modelDetail[0]->getFormattedOrderIdProperty($pemesananId),
                            'readonly' => 'true'
                        ])->label(false) ?>
                    </td>
                    <td>
                        <?= $form->field($modelDetail[0], '[0]nama_barang')->widget(Typeahead::classname(), [
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
                        ])->label(false); ?>
                        <?= Html::activeHiddenInput($modelDetail[0], '[0]barang_id') ?>
                    </td>
                    <td>
                        <?= $form->field($modelDetail[0], '[0]qty')->textInput(['id' => 'pesandetail-0-qty'])->label(false) ?>
                    </td>
                    <td>
                        <?= $form->field($modelDetail[0], '[0]catatan')->textInput(['maxlength' => true, 'id' => 'pesandetail-0-catatan'])->label(false) ?>
                    </td>
                    <td>
                        <?= $form->field($modelDetail[0], '[0]langsung_pakai')->checkbox(['id' => 'pesandetail-0-langsung_pakai', 'label' => null]) ?>
                    </td>
                    <td>
                        <?= Html::button('Remove', ['class' => 'btn btn-danger remove-item', 'data-id' => '0']) ?>
                    </td>
                    <?= Html::activeHiddenInput($modelDetail[0], '[0]qty_terima', ['value' => 0]) ?>
                    <?= Html::activeHiddenInput($modelDetail[0], '[0]is_correct', ['value' => 0]) ?>
                </tr>
            <?php else: ?>
                <?php foreach ($modelDetail as $index => $model): ?>
                    <tr id="pemesanan-item-<?= $index ?>">
                        <td>
                            <?= Html::activeHiddenInput($model, "[$index]pesandetail_id") ?>
                            <?= Html::activeHiddenInput($model, "[$index]pemesanan_id") ?>
                            <?= $form->field($model, "[$index]kode_pemesanan")->textInput(['value' => $formattedOrderId, 'id' => "pesandetail-{$index}-kode_pemesanan", 'readonly' => true])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$index]nama_barang")->textInput(['id' => "pesandetail-{$index}-nama_barang", 'readonly' => true, 'value' => $model->NamaBarang])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$index]qty")->textInput(['id' => "pesandetail-{$index}-qty", 'readonly' => true])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$index]catatan")->textInput(['maxlength' => true, 'id' => "pesandetail-{$index}-catatan"])->label(false) ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$index]langsung_pakai")->checkbox(['id' => "pesandetail-{$index}-langsung_pakai", 'disabled' => true])->label(false) ?>
                        </td>
                        <?php if (!$isCreate): ?>
                            <td>
                                <?= $form->field($model, "[$index]qty_terima")->textInput(['id' => "pesandetail-{$index}-qty_terima"])->label(false) ?>
                            </td>
                            <td>
                                <?= $form->field($model, "[$index]is_correct")->checkbox(['id' => "pesandetail-{$index}-is_correct", 'label' => 'Barang Sesuai'])->label(false) ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?= Html::button('Remove', ['class' => 'btn btn-danger remove-item', 'data-id' => $index]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div id="new-form-container"></div>

    <div class="form-group">
        <?php if ($isCreate): ?>
            <?= Html::button('Tambah Data Lain', ['class' => 'btn btn-success', 'id' => 'add-more']) ?>
        <?php endif; ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['pesan-detail/index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
// Variabel PHP untuk URL
$urlSearch = Url::to(['pesan-detail/search']);


$js = <<<JS
$(document).ready(function() {
    let index = 1; // Mulai dengan indeks 1 untuk form berikutnya
    const pemesananId = '{$pemesananId}'; // Menyimpan pemesanan_id dari session
    const isCreate = '{$isCreate}';
    const kodePemesanan = '{$formattedOrderId}'


    // Event listener untuk tombol tambah data
    $('#add-more').click(function() {
        let newForm = `
        <div class="pemesanan-item" id="pemesanan-item-\${index}">
            <div class="form-group">
                <input type="hidden" id="pesandetail-\${index}-pemesanan_id" class="form-control" name="PesanDetail[\${index}][pemesanan_id]" value="\${pemesananId}" readonly>
            </div>
            <div class="form-group">
                <label for="pesandetail-\${index}-kode_pemesanan">Kode Pemesanan</label>
                <input type="text" id="pesandetail-\${index}-kode_pemesanan" class="form-control" name="PesanDetail[\${index}][kode_pemesanan]" value="\${kodePemesanan}" readonly>
            </div>
            <div class="form-group">
                <input type="hidden" id="pesandetail-\${index}-barang_id" class="form-control" name="PesanDetail[\${index}][barang_id]">
            </div>
            <div class="form-group">
                <label for="pesandetail-\${index}-nama_barang">Nama Barang</label>
                <input type="text" id="pesandetail-\${index}-nama_barang" class="form-control" name="PesanDetail[\${index}][nama_barang]" required>
            </div>
            <div class="form-group">
                <label for="pesandetail-\${index}-qty">Qty</label>
                <input type="number" id="pesandetail-\${index}-qty" class="form-control" name="PesanDetail[\${index}][qty]" required>
            </div>

            <div class="form-group">
                <input type="hidden" id="pesandetail-\${index}-qty_terima" class="form-control" name="PesanDetail[\${index}][qty_terima]" value="0">
            </div>

            <div class="form-group">
                <label for="pesandetail-\${index}-catatan">Catatan</label>
                <input type="text" id="pesandetail-\${index}-catatan" class="form-control" name="PesanDetail[\${index}][catatan]">
            </div>
            <div class="form-group">
                <div class="checkbox">
                <input type="hidden" name="PesanDetail[\${index}][langsung_pakai]" value="0">
                    <label for="pesandetail-\${index}-langsung_pakai">
                        <input type="checkbox" id="pesandetail-\${index}-langsung_pakai" name="PesanDetail[\${index}][langsung_pakai]" value="1"> Langsung Pakai
                    </label>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" id="pesandetail-\${index}-is_correct" class="form-control" name="PesanDetail[\${index}][is_correct]" value="0">
            </div>
        `;

        if (!isCreate){
            newForm += `
            <div class="form-group">
                <label for="pesandetail-\${index}-qty_terima">Qty Terima</label>
                <input type="number" id="pesandetail-\${index}-qty_terima" class="form-control" name="PesanDetail[\${index}][qty_terima]" required>
            </div>
            <div class="form-group">
                <div class="checkbox">
                <input type="hidden" name="PesanDetail[\${index}][is_correct]" value="0">
                <label for="pesandetail-\${index}-is_correct">
                    <input type="checkbox" id="pesandetail-\${index}-is_correct" name="PesanDetail[\${index}][is_correct]" value="1"> Barang Sesuai
                </label>
                </div>
            </div>
                `;
        }
        newForm += `
            <div class="form-group">
                <button type="button" class="btn btn-danger remove-item" data-id="\${index}">Remove Data Lain</button>
            </div>
        </div>
        `;
        $('#new-form-container').append(newForm); // Tambah form baru ke container

        // Inisialisasi Typeahead untuk elemen yang baru saja ditambahkan
        initializeTypeahead(index);

        // Daftarkan elemen baru ke sistem validasi Yii
        $('#dynamic-form').yiiActiveForm('add', {
            id: 'pesandetail-' + index + '-barang_id',
            name: 'PesanDetail[' + index + '][barang_id]',
            container: '.field-pesandetail-' + index + '-barang_id',
            input: '#pesandetail-' + index + '-barang_id',
            validate: function(attribute, value, messages, deferred, \$form) {
                yii.validation.required(value, messages, {message: "Barang ID tidak boleh kosong."});
            }
        });

        $('#dynamic-form').yiiActiveForm('add', {
            id: 'pesandetail-' + index + '-qty',
            name: 'PesanDetail[' + index + '][qty]',
            container: '.field-pesandetail-' + index + '-qty',
            input: '#pesandetail-' + index + '-qty',
            validate: function(attribute, value, messages, deferred, \$form) {
                yii.validation.required(value, messages, {message: "Qty tidak boleh kosong."});
            }
        });

        $('#dynamic-form').yiiActiveForm('add', {
            id: 'pesandetail-' + index + '-qty_terima',
            name: 'PesanDetail[' + index + '][qty_terima]',
            container: '.field-pesandetail-' + index + '-qty_terima',
            input: '#pesandetail-' + index + '-qty_terima',
            validate: function(attribute, value, messages, deferred, \$form) {
                yii.validation.required(value, messages, {message: "Qty Terima tidak boleh kosong."});
            }
        });

        index++; // Naikkan indeks untuk form berikutnya
    });

    // Delegasi event untuk tombol remove, sehingga berfungsi untuk elemen dinamis
    $(document).on('click', '.remove-item', function() {
        let id = $(this).data('id');
        $('#pemesanan-item-' + id).remove(); // Hapus elemen form terkait
    });

    // Fungsi untuk menginisialisasi Typeahead pada elemen dinamis
    function initializeTypeahead(index) {
    $('#pesandetail-' + index + '-nama_barang').typeahead({
        highlight: true,
        minLength: 1
    }, {
        name: 'barang',
        display: 'value',  // Tampilkan nama_barang sebagai hasil
        source: function(query, syncResults, asyncResults) {
            $.get('{$urlSearch}?q=' + query, function(data) {
                if (Array.isArray(data)) {
                    asyncResults(data);
                }
            });
        },
        templates: {
            suggestion: function(data) {
                return "<div>" + data.barang_id + " - " + data.kode_barang + " - " + data.nama_barang + " - " + data.angka + " " + data.satuan + " - " + data.warna + "</div>";
            }
        }
    }).bind('typeahead:select', function(ev, suggestion) {
        // Isi otomatis field barang_id berdasarkan pilihan
        $('#pesandetail-' + index + '-barang_id').val(suggestion.barang_id);
        });
    }
});

JS;

// Daftarkan JavaScript ke halaman ini
$this->registerJs($js);
?>