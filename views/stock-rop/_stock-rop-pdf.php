
<?php
use yii\helpers\Html;

// Function untuk format periode
function formatPeriode($periode) {
    if (!$periode) return '-';
    
    $bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
        '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
        '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    
    // Cek apakah format YYYY-MM
    if (strpos($periode, '-') !== false) {
        $parts = explode('-', $periode);
        if (count($parts) == 2 && isset($bulan[$parts[1]])) {
            return $bulan[$parts[1]] . ' ' . $parts[0];
        }
    }
    
    // Jika format YYYYMM (tanpa strip)
    if (strlen($periode) == 6 && is_numeric($periode)) {
        $tahun = substr($periode, 0, 4);
        $bulanNum = substr($periode, 4, 2);
        if (isset($bulan[$bulanNum])) {
            return $bulan[$bulanNum] . ' ' . $tahun;
        }
    }
    
    // Fallback: tampilkan apa adanya
    return $periode;
}
?>

<h2>CV. DIGITAL WARNA MANDIRI</h2>
<h3>LAPORAN STOCK ROP (Reorder Point)</h3>

<?php if (!empty($stockRopData)): ?>
    <table class="table">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="10%">Kode Barang</th>
                <th width="20%">Nama Barang</th>
                <th width="10%">Periode</th>
                <th width="11%">Stock Barang</th>
                <th width="11%">Safety Stock</th>
                <th width="10%">EOQ</th>
                <th width="10%">ROP</th>
                <th width="14%">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($stockRopData as $data): 
                $satuan = $data->barang && $data->barang->unit ? $data->barang->unit->satuan : '';
                $status = $data->statusPesan;
                
                // Tentukan badge class
                $badgeClass = 'badge-success';
                if ($status == 'Pesan Sekarang') {
                    $badgeClass = 'badge-danger';
                } elseif ($status == 'Perlu Diperhatikan') {
                    $badgeClass = 'badge-warning';
                }
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= Html::encode($data->barang ? $data->barang->kode_barang : '-') ?></td>
                    <td><?= Html::encode($data->barang ? $data->barang->nama_barang : '-') ?></td>
                    <td class="text-center"><?= formatPeriode($data->periode) ?></td>
                    <td class="text-right">
                        <?= number_format($data->stock_barang, 0, ',', '.') ?> <?= $satuan ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($data->safety_stock, 0, ',', '.') ?> <?= $satuan ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($data->jumlah_eoq, 0, ',', '.') ?> <?= $satuan ?>
                    </td>
                    <td class="text-right">
                        <?= number_format($data->jumlah_rop, 0, ',', '.') ?> <?= $satuan ?>
                    </td>
                    <td class="text-center">
                        <span class="<?= $badgeClass ?>"><?= Html::encode($status) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align: center; color: #666; padding: 20px;">Tidak ada data untuk ditampilkan</p>
<?php endif; ?>

<div style="margin-top: 20px; font-size: 9px; color: #666;">
    <p>Dicetak pada: <?= date('d F Y H:i:s') ?></p>
</div>
