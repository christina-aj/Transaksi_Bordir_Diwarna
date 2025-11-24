<?php
use yii\helpers\Html;

// Proses data untuk grouping per barang
$dataByBarang = [];
$areaList = [];

foreach ($stokData as $item) {
    $barangKey = $item['kode_barang'];
    
    if (!isset($dataByBarang[$barangKey])) {
        $dataByBarang[$barangKey] = [
            'kode_barang' => $item['kode_barang'],
            'nama_barang' => $item['nama_barang'] ?? '-',
            'unit_id' => $item['unit_id'] ?? null,
            'satuan' => null,
            'areas' => []
        ];
    }
    
    $area = $item['area_gudang'];
    $dataByBarang[$barangKey]['areas'][$area] = $item['total_stok'];
    
    if (!in_array($area, $areaList)) {
        $areaList[] = $area;
    }
}

sort($areaList);

// Ambil data satuan dari tabel unit
$unitData = [];
$units = Yii::$app->db->createCommand("SELECT unit_id, satuan FROM inventaris_web.unit")->queryAll();
foreach ($units as $unit) {
    $unitData[$unit['unit_id']] = $unit['satuan'];
}

// Mapping satuan ke barang
foreach ($dataByBarang as $key => $data) {
    if ($data['unit_id'] && isset($unitData[$data['unit_id']])) {
        $dataByBarang[$key]['satuan'] = $unitData[$data['unit_id']];
    }
}

// Function untuk format nama area
function formatAreaName($area) {
    return $area . ' - ' . ucfirst(getAreaLabel($area));
}

function getAreaLabel($area) {
    $labels = [
        '1' => 'Depan',
        '2' => 'Bawah Tangga',
        '3' => 'Lantai 2',
        '4' => 'Garasi (Barang Jadi)',
        '5' => 'Produksi',
    ];
    return $labels[$area] ?? '';
}
?>

<h2 style="text-align: center; margin-bottom: 5px;">CV. DIGITAL WARNA MANDIRI</h2>
<h2 style="text-align: center; margin-bottom: 5px;">Laporan Stok Gudang</h2>
<?php if ($tanggal): ?>
    <p class="info" style="text-align: center;">Sampai Tanggal: <?= date('d F Y', strtotime($tanggal)) ?></p>
<?php else: ?>
    <p class="info" style="text-align: center;">Per Tanggal: <?= date('d F Y') ?></p>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th rowspan="2" width="30px" class="text-center">No</th>
            <th rowspan="2">Nama Barang</th>
            <?php foreach ($areaList as $area): ?>
                <th class="text-center"><?= formatAreaName($area) ?></th>
            <?php endforeach; ?>
            <th rowspan="2" class="text-center" style="background-color: #37903cff;">Total Semua Gudang</th>
        </tr>
        <tr>
            <?php foreach ($areaList as $area): ?>
                <th class="text-center">Kuantitas</th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $grandTotalQty = 0;
        $areaTotals = array_fill_keys($areaList, 0);
        
        foreach ($dataByBarang as $data):
            $rowTotalQty = 0;
            $satuan = $data['satuan'] ? ' ' . $data['satuan'] : '';
        ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= Html::encode($data['nama_barang']) ?></td>
                
                <?php foreach ($areaList as $area): ?>
                    <?php 
                    $qty = $data['areas'][$area] ?? 0;
                    $rowTotalQty += $qty;
                    $areaTotals[$area] += $qty;
                    ?>
                    <td class="text-center">
                        <?= $qty > 0 ? number_format($qty, 0, ',', '.') . $satuan : '0' . $satuan ?>
                    </td>
                <?php endforeach; ?>
                
                <td class="text-center">
                    <strong><?= number_format($rowTotalQty, 0, ',', '.') . $satuan ?></strong>
                </td>
            </tr>
        <?php 
            $grandTotalQty += $rowTotalQty;
        endforeach; 
        ?>
        
        <!-- Total Row -->
        <tr style="background-color: #e3f2fd; font-weight: bold;">
            <td colspan="2" class="text-center">Total Semua Barang</td>
            <?php foreach ($areaList as $area): ?>
                <td class="text-center"><?= number_format($areaTotals[$area], 0, ',', '.') ?></td>
            <?php endforeach; ?>
            <td class="text-center"><?= number_format($grandTotalQty, 0, ',', '.') ?></td>
        </tr>
    </tbody>
</table>

<?php if (empty($stokData)): ?>
    <p style="text-align: center; color: #666;">Tidak ada data untuk ditampilkan</p>
<?php endif; ?>