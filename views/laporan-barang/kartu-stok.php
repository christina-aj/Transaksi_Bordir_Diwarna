<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Laporan Kartu Stok';

// Function untuk format tanggal Indonesia
function formatTanggalIndo($tanggal) {
    if (!$tanggal) return '-';
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>

<div class="pc-content">
    <div class="laporan-kartu-stok">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <!-- Panel Filter -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-filter"></i> Filter Kartu Stok</h3>
            </div>
            <div class="panel-body">
                <?= Html::beginForm(['laporan-barang/kartu-stok'], 'get', ['class' => 'form-horizontal']) ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Barang:</label>
                            <div class="col-sm-8">
                                <?= Html::dropDownList(
                                    'barang_id', 
                                    $barang_id, 
                                    $barangList, 
                                    [
                                        'class' => 'form-control',
                                        'prompt' => '-- Pilih Barang --',
                                        'required' => true
                                    ]
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Area Gudang:</label>
                            <div class="col-sm-8">
                                <?= Html::dropDownList(
                                    'area_gudang', 
                                    $area_gudang, 
                                    array_combine($areaGudangList, $areaGudangList), 
                                    [
                                        'class' => 'form-control',
                                        'prompt' => '-- Pilih Area --',
                                        'required' => true
                                    ]
                                ) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Dari:</label>
                            <div class="col-sm-8">
                                <?= Html::input('date', 'tanggal_dari', $tanggal_dari, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Tanggal Sampai:</label>
                            <div class="col-sm-8">
                                <?= Html::input('date', 'tanggal_sampai', $tanggal_sampai, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Tampilkan', ['class' => 'btn btn-primary']) ?>
                        
                        <?php if ($barang_id && $area_gudang): ?>
                            <?= Html::a(
                                '<i class="glyphicon glyphicon-file"></i> Download PDF', 
                                [
                                    'laporan-barang/kartu-stok-pdf', 
                                    'barang_id' => $barang_id,
                                    'area_gudang' => $area_gudang,
                                    'tanggal_dari' => $tanggal_dari,
                                    'tanggal_sampai' => $tanggal_sampai
                                ],
                                ['class' => 'btn btn-danger', 'target' => '_blank']
                            ) ?>
                        <?php endif; ?>
                        
                        <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['laporan-barang/kartu-stok'], ['class' => 'btn btn-default']) ?>
                    </div>
                </div>
                
                <?= Html::endForm() ?>
            </div>
        </div>
        
        <?php if ($barang && !empty($kartuStokData)): ?>
            <!-- Info Barang -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="glyphicon glyphicon-info-sign"></i> Informasi Barang</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-condensed">
                                <tr>
                                    <td width="150"><strong>Kode Barang</strong></td>
                                    <td>: <?= Html::encode($barang->kode_barang) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Barang</strong></td>
                                    <td>: <?= Html::encode($barang->nama_barang) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Warna</strong></td>
                                    <td>: <?= Html::encode($barang->warna ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-condensed">
                                <tr>
                                    <td width="150"><strong>Area Gudang</strong></td>
                                    <td>: <?= Html::encode($area_gudang) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Periode</strong></td>
                                    <td>: <?= $tanggal_dari ? formatTanggalIndo($tanggal_dari) : 'Awal' ?> s/d <?= $tanggal_sampai ? formatTanggalIndo($tanggal_sampai) : 'Sekarang' ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Stok Awal</strong></td>
                                    <td>: <strong><?= number_format($stokAwal, 0, ',', '.') ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tabel Kartu Stok -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead style="background-color: #4CAF50; color: white;">
                        <tr>
                            <th width="40px" class="text-center">No</th>
                            <th width="100px" class="text-center">Tanggal</th>
                            <th>catatan</th>
                            <th width="120px" class="text-center">Qty Masuk</th>
                            <th width="120px" class="text-center">Qty Keluar</th>
                            <th width="120px" class="text-center">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris stok Awal -->
                        <tr style="background-color: #fff3cd; font-weight: bold;">
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td>STOK AWAL</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center"><?= number_format($stokAwal, 0, ',', '.') ?></td>
                        </tr>
                        
                        <?php 
                        $no = 1;
                        $totalMasuk = 0;
                        $totalKeluar = 0;
                        
                        foreach ($kartuStokData as $data):
                            $totalMasuk += $data['quantity_masuk'];
                            $totalKeluar += $data['quantity_keluar'];
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                                <td><?= Html::encode($data['catatan']) ?></td>
                                <td class="text-right">
                                    <?= $data['quantity_masuk'] > 0 ? number_format($data['quantity_masuk'], 0, ',', '.') : '-' ?>
                                </td>
                                <td class="text-right">
                                    <?= $data['quantity_keluar'] > 0 ? number_format($data['quantity_keluar'], 0, ',', '.') : '-' ?>
                                </td>
                                <td class="text-right">
                                    <strong><?= number_format($data['stok'], 0, ',', '.') ?></strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- Total Row -->
                        <tr style="background-color: #e3f2fd; font-weight: bold;">
                            <td colspan="3" class="text-right">TOTAL</td>
                            <td class="text-right"><?= number_format($totalMasuk, 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($totalKeluar, 0, ',', '.') ?></td>
                            <td class="text-right" style="background-color: #4CAF50; color: white;">
                                <?= number_format(end($kartuStokData)['stok'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
        <?php elseif ($barang_id && $area_gudang): ?>
            <div class="alert alert-info">
                <i class="glyphicon glyphicon-info-sign"></i> Tidak ada data transaksi untuk barang dan area gudang yang dipilih pada periode tersebut.
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="glyphicon glyphicon-warning-sign"></i> Silakan pilih barang dan area gudang untuk menampilkan kartu stok.
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.table-condensed td {
    padding: 5px;
    border: none;
}
.panel {
    margin-bottom: 20px;
}
</style>