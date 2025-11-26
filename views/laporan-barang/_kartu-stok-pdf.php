<?php
use yii\helpers\Html;

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

<h2>CV. DIGITAL WARNA MANDIRI</h2>
<h3>LAPORAN KARTU STOK</h3>

<?php if ($barang): ?>
    <div class="barang-info">
        <table>
            <tr>
                <td>Kode Barang</td>
                <td>: <?= Html::encode($barang->kode_barang) ?></td>
                <td>Area Gudang</td>
                <td>: <?= Html::encode($area_gudang) ?></td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td>: <?= Html::encode($barang->nama_barang) ?></td>
                <td>Periode</td>
                <td>: <?= $tanggal_dari ? formatTanggalIndo($tanggal_dari) : 'Awal' ?> s/d <?= $tanggal_sampai ? formatTanggalIndo($tanggal_sampai) : 'Sekarang' ?></td>
            </tr>
            <tr>
                <td>Warna</td>
                <td>: <?= Html::encode($barang->warna ?? '-') ?></td>
                <td>Stok Awal</td>
                <td>: <strong><?= number_format($stokAwal, 0, ',', '.') ?></strong></td>
            </tr>
        </table>
    </div>
<?php endif; ?>

<?php if (!empty($kartuStokData)): ?>
    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th width="40%">Keterangan</th>
                <th width="13%">Qty Masuk</th>
                <th width="13%">Qty Keluar</th>
                <th width="13%">Stok</th>
            </tr>
        </thead>
        <tbody>
            <!-- Baris Stok Awal -->
            <tr class="stok-awal">
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
            $stokAkhir = $stokAwal;
            
            foreach ($kartuStokData as $data):
                $totalMasuk += $data['quantity_masuk'];
                $totalKeluar += $data['quantity_keluar'];
                $stokAkhir = $data['stok']; // Simpan stok terakhir
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d/m/Y', strtotime($data['tanggal'])) ?></td>
                    <td><?= Html::encode($data['keterangan']) ?></td>
                    <td class="text-right">
                        <?= $data['quantity_masuk'] > 0 ? number_format($data['quantity_masuk'], 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-right">
                        <?= $data['quantity_keluar'] > 0 ? number_format($data['quantity_keluar'], 0, ',', '.') : '-' ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($data['stok'], 0, ',', '.') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong><?= number_format($totalMasuk, 0, ',', '.') ?></strong></td>
                <td class="text-right"><strong><?= number_format($totalKeluar, 0, ',', '.') ?></strong></td>
                <td class="text-right total-stok">
                    <strong><?= number_format($stokAkhir, 0, ',', '.') ?></strong>
                </td>
            </tr>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center; color: #666; padding: 20px;">Tidak ada data untuk ditampilkan</p>
<?php endif; ?>

<div style="margin-top: 20px; font-size: 9px; color: #666;">
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
</div>