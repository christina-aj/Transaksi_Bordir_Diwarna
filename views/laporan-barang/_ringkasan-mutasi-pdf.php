<?php
use yii\helpers\Html;

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

// Format periode
$periode = '';
if ($tanggal_dari && $tanggal_sampai) {
    $periode = 'Dari ' . date('d M Y', strtotime($tanggal_dari)) . ' s/d ' . date('d M Y', strtotime($tanggal_sampai));
} elseif ($tanggal_dari) {
    $periode = 'Dari ' . date('d M Y', strtotime($tanggal_dari));
} elseif ($tanggal_sampai) {
    $periode = 'Sampai ' . date('d M Y', strtotime($tanggal_sampai));
} else {
    $periode = 'Semua Periode';
}
?>

<h2 style="text-align: center; margin-bottom: 5px;">CV. DIGITAL WARNA MANDIRI</h2>
<h2 style="text-align: center; margin-bottom: 5px;">Ringkasan Mutasi Gudang</h2>
<p class="subtitle"><?= $periode ?></p>

<table class="table">
    <thead>
        <tr>
            <th>Nama Barang</th>
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
                    <td style="padding-left: 15px;"><?= Html::encode($item['nama_barang']) ?></td>
                    <td class="text-right"><?= number_format($item['saldo_awal'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($item['total_masuk'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($item['total_keluar'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($item['saldo_akhir'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Subtotal Area -->
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td class="text-right">Subtotal</td>
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

<?php if (empty($mutasiData)): ?>
    <p style="text-align: center; color: #666;">Tidak ada data untuk ditampilkan</p>
<?php endif; ?>