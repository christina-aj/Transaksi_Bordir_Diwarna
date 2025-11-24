<?php
use yii\helpers\Html;

$this->title = 'Laporan Stok per Gudang';

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

<div class="pc-content">
    <div class="laporan-barang-stok-gudang">
        <h1><?= Html::encode($this->title) ?></h1>
        
        <!-- Panel Filter -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="glyphicon glyphicon-filter"></i> Filter Laporan</h3>
            </div>
            <div class="panel-body">
                <?= Html::beginForm(['laporan-barang/stok-gudang'], 'get', ['class' => 'form-inline']) ?>
                <div class="form-group" style="margin-right: 10px;">
                    <label>Sampai Tanggal:</label>
                    <?= Html::input('date', 'tanggal', $tanggal, ['class' => 'form-control']) ?>
                </div>
                
                <div class="form-group">
                    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> Tampilkan', ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(
                        '<i class="glyphicon glyphicon-file"></i> Download PDF', 
                        ['laporan-barang/stok-gudang-pdf', 'tanggal' => $tanggal],
                        ['class' => 'btn btn-danger', 'target' => '_blank']
                    ) ?>
                    <?= Html::a('<i class="glyphicon glyphicon-refresh"></i> Reset', ['laporan-barang/stok-gudang'], ['class' => 'btn btn-default']) ?>
                </div>
                <?= Html::endForm() ?>
            </div>
        </div>
        
        <!-- Tabel Data -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead style="background-color: #4CAF50; color: white;">
                    <tr>
                        <th rowspan="2" width="40px" class="text-center">No</th>
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
                        <td colspan="2" class="text-right">Total Semua Barang</td>
                        <?php foreach ($areaList as $area): ?>
                            <td class="text-center"><?= number_format($areaTotals[$area], 0, ',', '.') ?></td>
                        <?php endforeach; ?>
                        <td class="text-center" style="background-color: #4CAF50; color: white;">
                            <?= number_format($grandTotalQty, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php if (empty($stokData)): ?>
            <div class="alert alert-info">
                <i class="glyphicon glyphicon-info-sign"></i> Tidak ada data stok untuk ditampilkan.
            </div>
        <?php endif; ?>
    </div>
</div>