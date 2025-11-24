<?php
use yii\helpers\Html;

$this->title = 'Ringkasan Mutasi Gudang';

// Ambil data satuan
$unitData = [];
$units = Yii::$app->db->createCommand("SELECT unit_id, satuan FROM inventaris_web.unit")->queryAll();
foreach ($units as $unit) {
    $unitData[$unit['unit_id']] = $unit['satuan'];
}

// Group data by area
$dataByArea = [];
foreach ($mutasiData as $item) {
    $area = $item['area_gudang'];
    if (!isset($dataByArea[$area])) {
        $dataByArea[$area] = [];
    }
    $dataByArea[$area][] = $item;
}
?>
<div class="pc-content">
    <div class="laporan-ringkasan-mutasi">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <!-- Panel Filter -->
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-filter"></i> Filter Periode</h3>
            </div>
            <div class="panel-body">
                <?= Html::beginForm(['laporan-barang/ringkasan-mutasi'], 'get', ['class' => 'form-inline']) ?>
                <div class="form-group" style="margin-right: 10px;">
                    <label>Dari Tanggal:</label>
                    <?= Html::input('date', 'tanggal_dari', $tanggal_dari, ['class' => 'form-control']) ?>
                </div>
                
                <div class="form-group" style="margin-right: 10px;">
                    <label>Sampai Tanggal:</label>
                    <?= Html::input('date', 'tanggal_sampai', $tanggal_sampai, ['class' => 'form-control']) ?>
                </div>
                
                <div class="form-group">
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Tampilkan', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(
                        '<i class="glyphicon glyphicon-file"></i> Download PDF', 
                        ['laporan-barang/ringkasan-mutasi-pdf', 'tanggal_dari' => $tanggal_dari, 'tanggal_sampai' => $tanggal_sampai],
                        ['class' => 'btn btn-danger', 'target' => '_blank']
                    ) ?>
                    <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['laporan-barang/ringkasan-mutasi'], ['class' => 'btn btn-default']) ?>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead style="background-color: #e91e63; color: white;">
                    <tr>
                        <th class="text-center">Nama Barang</th>
                        <th class="text-center">Stok Awal</th>
                        <th class="text-center">Masuk</th>
                        <th class="text-center">Keluar</th>
                        <th class="text-center">Stok Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $grandSaldoAwal = 0;
                    $grandMasuk = 0;
                    $grandKeluar = 0;
                    $grandSaldoAkhir = 0;
                    
                    foreach ($dataByArea as $area => $items):
                        $areaSaldoAwal = 0;
                        $areaMasuk = 0;
                        $areaKeluar = 0;
                        $areaSaldoAkhir = 0;
                    ?>
                        <!-- Header Area -->
                        <tr class="area-header">
                            <td colspan="5">
                                <strong>Area Gudang : <?= Html::encode($area) ?></strong>
                            </td>
                        </tr>
                        
                        <?php foreach ($items as $item): 
                            $satuan = isset($unitData[$item['unit_id']]) ? ' ' . $unitData[$item['unit_id']] : '';
                            $areaSaldoAwal += $item['saldo_awal'];
                            $areaMasuk += $item['total_masuk'];
                            $areaKeluar += $item['total_keluar'];
                            $areaSaldoAkhir += $item['saldo_akhir'];
                        ?>
                            <tr>
                                <td style="padding-left: 20px;"><?= Html::encode($item['nama_barang']) ?></td>
                                <td class="text-right"><?= number_format($item['saldo_awal'], 0, ',', '.') . $satuan ?></td>
                                <td class="text-right"><?= number_format($item['total_masuk'], 0, ',', '.') . $satuan ?></td>
                                <td class="text-right"><?= number_format($item['total_keluar'], 0, ',', '.') . $satuan ?></td>
                                <td class="text-right"><strong><?= number_format($item['saldo_akhir'], 0, ',', '.') . $satuan ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- Subtotal Area -->
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td class="text-right">Subtotal <?= Html::encode($area) ?></td>
                            <td class="text-right"><?= number_format($areaSaldoAwal, 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($areaMasuk, 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($areaKeluar, 0, ',', '.') ?></td>
                            <td class="text-right"><?= number_format($areaSaldoAkhir, 0, ',', '.') ?></td>
                        </tr>
                        
                        <?php
                        $grandSaldoAwal += $areaSaldoAwal;
                        $grandMasuk += $areaMasuk;
                        $grandKeluar += $areaKeluar;
                        $grandSaldoAkhir += $areaSaldoAkhir;
                        ?>
                    <?php endforeach; ?>
                    
                    <!-- Grand Total -->
                    <tr class="total-row">
                        <td class="text-right"><strong>TOTAL</strong></td>
                        <td class="text-right"><strong><?= number_format($grandSaldoAwal, 0, ',', '.') ?></strong></td>
                        <td class="text-right"><strong><?= number_format($grandMasuk, 0, ',', '.') ?></strong></td>
                        <td class="text-right"><strong><?= number_format($grandKeluar, 0, ',', '.') ?></strong></td>
                        <td class="text-right"><strong><?= number_format($grandSaldoAkhir, 0, ',', '.') ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($mutasiData)): ?>
            <div class="alert alert-info">
                <i class="glyphicon glyphicon-info-sign"></i> Tidak ada data mutasi untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>
</div>