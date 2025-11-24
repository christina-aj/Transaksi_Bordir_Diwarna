<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\Gudang;
use app\models\Pembelian;
use app\models\PembelianDetail;
use app\models\Pemesanan;
use app\models\PemesananSearch;
use app\models\PesanDetail;
use app\models\Stock;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PemesananController implements the CRUD actions for Pemesanan model.
 */
class PemesananController extends BaseController
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['delete', 'update', 'create', 'index', 'view'], // Aksi yang diatur
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // Hanya pengguna yang sudah login
                        ],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all Pemesanan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PemesananSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pemesanan model.
     * @param int $pemesanan_id Pemesanan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pemesanan_id)
    {
        
        // Temukan model Pemesanan berdasarkan pemesanan_id
        $model = $this->findModel($pemesanan_id);

        // Mengambil semua PesanDetail yang terkait dengan pemesanan ini
        $pesanDetails = $model->pesanDetails;

        // Mengirim model dan pesanDetails ke view
        return $this->render('view', [
            'model' => $model,
            'pesanDetails' => $pesanDetails, // Pastikan $pesanDetails diteruskan
        ]);
    }
    public function actionDashboard($pemesanan_id)
    {
        // Temukan model Pemesanan berdasarkan pemesanan_id
        $model = $this->findModel($pemesanan_id);

        // Mengambil semua PesanDetail yang terkait dengan pemesanan ini
        $pesanDetails = $model->pesanDetails;

        // Mengirim model dan pesanDetails ke view
        return $this->render('//site/index', [
            'model' => $model,
            'pesanDetails' => $pesanDetails, // Pastikan $pesanDetails diteruskan
        ]);
    }

    /**
     * Creates a new Pemesanan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $permintaanId = Yii::$app->request->get('permintaan_id');

        $stockRopId = Yii::$app->request->get('stock_rop_id');

        $modelPemesanan = new Pemesanan();

        // Set default data
        $modelPemesanan->tanggal = date('Y-m-d');
        $modelPemesanan->user_id = Yii::$app->user->identity->user_id;
        $modelPemesanan->total_item = 0;
        $modelPemesanan->status = Pemesanan::STATUS_PENDING;
        $modelPemesanan->created_at = date('Y-m-d H:i:s');
        $modelPemesanan->updated_at = date('Y-m-d H:i:s');

        $modelPemesanan->permintaan_id = $permintaanId;

        $modelPemesanan->stock_rop_id = $stockRopId;

        // Retrieve the user's name
        $user = User::findOne($modelPemesanan->user_id);
        $modelPemesanan->nama_pemesan = $user->nama_pengguna;

        // Generate a temporary order code
        $kodeSementara = Pemesanan::find()->max('pemesanan_id') + 1;
        $modelPemesanan->kode_pemesanan = 'FPB-' . str_pad($kodeSementara, 3, '0', STR_PAD_LEFT);

        // Simpan data `Pemesanan` dan lanjutkan ke pembuatan pembelian
        if ($modelPemesanan->save()) {
            // Set session untuk ID Pemesanan agar bisa digunakan di `actionCreatePembelian`
            Yii::$app->session->set('temporaryOrderId', $modelPemesanan->pemesanan_id);

            // Redirect ke `CreatePembelian`
            return $this->redirect([
                'create-pembelian',
                'permintaan_id' => $permintaanId,
                'stock_rop_id' => $stockRopId
            ]);

        } else {
            Yii::$app->session->setFlash('error', 'Gagal membuat pemesanan.');
            return $this->redirect(['index']);
        }
    }

    public function actionCreatePembelian()
    {
        // Ambil ID Pemesanan dari session
        $pemesananId = Yii::$app->session->get('temporaryOrderId');
        if (!$pemesananId) {
            Yii::$app->session->setFlash('error', 'ID pemesanan tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Ambil permintaan_id dari URL
        $permintaanId = Yii::$app->request->get('permintaan_id');
        $stockRopId = Yii::$app->request->get('stock_rop_id');

        // Buat pembelian baru
        $pembelian = new Pembelian();
        $pembelian->pemesanan_id = $pemesananId; // Mengaitkan dengan pemesanan
        $pembelian->user_id = Yii::$app->user->identity->user_id;
        $pembelian->total_biaya = 0; // Set total biaya ke 0

        // Simpan pembelian dan cek apakah berhasil
        if ($pembelian->save()) {
            Yii::debug("Pembelian berhasil dibuat dengan ID: " . $pembelian->pembelian_id, __METHOD__);

            // Redirect ke `AddDetails` untuk menambahkan detail pesanan
            return $this->redirect([
                'add-details',
                'pemesanan_id' => $pemesananId,
                'pembelian_id' => $pembelian->pembelian_id,
                'permintaan_id' => $permintaanId,
                'stock_rop_id' => $stockRopId
            ]);
        } else {
            Yii::error("Gagal membuat pembelian: " . json_encode($pembelian->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    public function actionAddDetails($pemesanan_id, $pembelian_id)
    {
        // Ambil permintaan_id dari URL
        $permintaanId = Yii::$app->request->get('permintaan_id');
        
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

        $modelDetails = [new PesanDetail()];

        if (Yii::$app->request->isPost) {
            $modelDetails = ModelHelper::createMultiple(PesanDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            if (Model::validateMultiple($modelDetails)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // Hapus detail lama
                    PesanDetail::deleteAll(['pemesanan_id' => $pemesanan_id]);
                    
                    foreach ($modelDetails as $index => $modelDetail) {
                        $barang = Barang::findOne($modelDetail->barang_id);
                        $modelDetail->kode_pemesanan = $barang->kode_barang;
                        $modelDetail->pemesanan_id = $pemesanan_id;
                        $modelDetail->created_at = date('Y-m-d H:i:s');
                        $modelDetail->langsung_pakai = !empty(Yii::$app->request->post('PesanDetail')[$index]['langsung_pakai']) ? 1 : 0;
                        $modelDetail->is_correct = !empty(Yii::$app->request->post('PesanDetail')[$index]['is_correct']) ? 1 : 0;

                        if (!$modelDetail->save()) {
                            Yii::$app->session->setFlash('error', "Gagal menyimpan detail pemesanan ke-{$index}: " . json_encode($modelDetail->getErrors()));
                            throw new \Exception('Gagal menyimpan detail pemesanan.');
                        }
                    }

                    // Update total item
                    $modelPemesanan->total_item = count($modelDetails);
                    $modelPemesanan->save(false);
                    
                    $transaction->commit();

                    // Simpan modelDetails ke session
                    Yii::$app->session->set('modelDetails', $modelDetails);

                    return $this->redirect(['create-pembelian-detail', 'pembelianId' => $pembelian_id, 'pemesananId' => $pemesanan_id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validasi gagal: ' . json_encode($modelDetails[0]->getErrors()));
            }
        }

        return $this->render('create', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
            'isReadonly' => true,
            'permintaanId' => $permintaanId,
        ]);
    }

    public function actionCreatePembelianDetail($pembelianId, $pemesananId)
    {
        // Ambil modelDetails dari session
        $modelDetails = Yii::$app->session->get('modelDetails');
        if (!$modelDetails || !is_array($modelDetails)) {
            Yii::$app->session->setFlash('error', 'Data modelDetails tidak valid atau tidak ditemukan.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($modelDetails as $pesanDetail) {
                if (!$pesanDetail instanceof PesanDetail) {
                    Yii::$app->session->setFlash('error', 'Objek pesan detail tidak valid.');
                    throw new \Exception('Objek dalam modelDetails bukan instance dari PesanDetail.');
                }

                // AMBIL SUPPLIER UTAMA =====
                $supplierUtamaId = $this->getSupplierUtama($pesanDetail->barang_id);

                //AMBIL HARGA SUPP UTAMA
                $hargaSuppUtama = $this->getHargaSupplierUtama($pesanDetail->barang_id, $supplierUtamaId);

                // Hitung total biaya = qty × harga
                $totalBiaya = $pesanDetail->qty * $hargaSuppUtama;

                $pembelianDetail = new PembelianDetail();
                $pembelianDetail->pembelian_id = $pembelianId;
                $pembelianDetail->pesandetail_id = $pesanDetail->pesandetail_id;
                $pembelianDetail->cek_barang = $hargaSuppUtama;
                $pembelianDetail->total_biaya = $totalBiaya;
                $pembelianDetail->supplier_id = $supplierUtamaId;
                $pembelianDetail->is_correct = 0;
                $pembelianDetail->created_at = date('Y-m-d H:i:s');

                if (!$pembelianDetail->save()) {
                    Yii::$app->session->setFlash('error', 'Gagal membuat pembelian detail.');
                    throw new \Exception('Gagal menyimpan pembelian detail: ' . json_encode($pembelianDetail->getErrors()));
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Semua pembelian detail berhasil disimpan.');

            // Hapus modelDetails dari session setelah selesai
            Yii::$app->session->remove('modelDetails');

            return $this->redirect(['view', 'pemesanan_id' => $pemesananId]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
    }
    /**
     * Updates an existing Pemesanan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pemesanan_id Pemesanan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pemesanan_id)
    {
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

        // Ambil detail pemesanan yang terkait
        $modelDetails = PesanDetail::findAll(['pemesanan_id' => $modelPemesanan->pemesanan_id]);

        // Set nama_barang untuk setiap modelDetail jika dalam mode update
        foreach ($modelDetails as $modelDetail) {
            $modelDetail->nama_barang = $modelDetail->getNamaBarang();
            $modelDetail->kode_barang = $modelDetail->getKodeBarang();
        }

        if (Yii::$app->request->isPost) {
            $modelPemesanan->load(Yii::$app->request->post());

            // Ambil data detail dari POST
            $detailData = Yii::$app->request->post('PesanDetail', []);
            $isValid = $modelPemesanan->validate();

            // Loop untuk menyimpan atau memperbarui detail yang ada
            foreach ($modelDetails as $index => $modelDetail) {
                if (isset($detailData[$index])) {
                    $modelDetail->setAttributes($detailData[$index]);
                    $isValid = $modelDetail->validate() && $isValid;
                }
            }

            // Loop untuk menambah detail baru jika ada data baru
            $newDetails = [];
            foreach ($detailData as $index => $data) {
                if (!isset($modelDetails[$index])) { // Detail baru
                    $newDetail = new PesanDetail();
                    $newDetail->pemesanan_id = $modelPemesanan->pemesanan_id;
                    $newDetail->setAttributes($data);
                    $newDetails[] = $newDetail;
                    $isValid = $newDetail->validate() && $isValid;
                }
            }

            // Hapus PesanDetail yang dihapus oleh user beserta PembelianDetail yang terkait
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $deletedDetails = json_decode(Yii::$app->request->post('deleteRows', '[]'), true);
                if (is_array($deletedDetails)) {
                    foreach ($deletedDetails as $deletedId) {
                        $deletedDetail = PesanDetail::findOne($deletedId);
                        if ($deletedDetail) {
                            // Hapus semua PembelianDetail terkait dengan pesandetail_id ini
                            PembelianDetail::deleteAll(['pesandetail_id' => $deletedDetail->pesandetail_id]);

                            // Hapus PesanDetail
                            $deletedDetail->delete();
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error("Gagal menghapus data: " . $e->getMessage());
            }

            // Jika valid, simpan perubahan di Pemesanan dan detailnya
            if ($isValid) {
                $modelPemesanan->save(false); // Simpan tanpa validasi karena sudah divalidasi di atas

                // Simpan detail yang diubah saja tanpa sinkronisasi ke PembelianDetail yang telah dihapus
                foreach ($modelDetails as $modelDetail) {
                    $modelDetail->save(false); // Simpan tanpa validasi
                }

                // Simpan detail baru dan tambahkan PembelianDetail baru jika ada detail baru
                $pembelian = Pembelian::findOne(['pemesanan_id' => $modelPemesanan->pemesanan_id]);
                foreach ($newDetails as $newDetail) {
                    if ($newDetail->save(false)) { // Simpan PesanDetail terlebih dahulu tanpa validasi
                        // AMBIL SUPPLIER UTAMA 
                        $supplierUtamaId = $this->getSupplierUtama($newDetail->barang_id);
                        //AMBIL HARGA SUPP UTAMA
                        $hargaSuppUtama = $this->getHargaSupplierUtama($newDetail->barang_id, $supplierUtamaId);
                        // Hitung total biaya = qty × harga
                        $totalBiaya = $newDetail->qty * $hargaSuppUtama;
                        // Buat PembelianDetail baru untuk setiap PesanDetail yang baru disimpan
                        $pembelianDetail = new PembelianDetail([
                            'pembelian_id' => $pembelian->pembelian_id,
                            'pesandetail_id' => $newDetail->pesandetail_id, // Gunakan pesandetail_id dari PesanDetail yang baru disimpan
                            'cek_barang' => $hargaSuppUtama,
                            'total_biaya' => $totalBiaya,
                            'supplier_id' => $supplierUtamaId,
                            'is_correct' => 0,
                        ]);
                        $pembelianDetail->save(false); // Simpan PembelianDetail tanpa validasi
                    }
                }

                Yii::$app->session->setFlash('success', 'Pemesanan dan detail pembelian berhasil diperbarui.');
                return $this->redirect(['view', 'pemesanan_id' => $modelPemesanan->pemesanan_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data pemesanan. Harap periksa kembali data yang dimasukkan.');
            }
        }

        return $this->render('update', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
        ]);
    }

    public function actionUpdateQty($pemesanan_id)
    {
        // Memuat model Pemesanan berdasarkan ID
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pembelian tidak ditemukan.");
        }

        $modelDetails = PesanDetail::findAll(['pemesanan_id' => $pemesanan_id]);

        if (Yii::$app->request->isPost) {
            $detailsData = Yii::$app->request->post('PemesananDetail', []);
            $isValid = true;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Simpan area_gudang ke session untuk digunakan di actionVerify
                $areaGudangData = [];

                foreach ($detailsData as $id => $attributes) {
                    $detailModel = PesanDetail::findOne($id);
                    if ($detailModel) {
                        $detailModel->setScenario('updateQty'); // Set skenario khusus
                        $detailModel->qty_terima = $attributes['qty_terima'] ?? $detailModel->qty_terima;
                        $detailModel->catatan = $attributes['catatan'] ?? $detailModel->catatan;
                        $detailModel->is_correct = isset($attributes['is_correct']) ? 1 : 0;

                        // Simpan area_gudang ke array (tidak disimpan ke database PesanDetail)
                        if (isset($attributes['area_gudang'])) {
                            $areaGudangData[$id] = $attributes['area_gudang'];
                        }

                        if (!$detailModel->validate() || !$detailModel->save(false)) {
                            Yii::$app->session->setFlash('error', "Error pada detail ID {$id}: " . json_encode($detailModel->getErrors()));
                            Yii::error("Validasi gagal untuk detail ID {$id}: " . json_encode($detailModel->getErrors()));
                            throw new \Exception("Gagal menyimpan data untuk detail ID {$id}");
                        }
                    } else {
                        throw new \Exception("Detail Pemesanan dengan ID {$id} tidak ditemukan.");
                    }
                }
                // Simpan area_gudang data ke session
                Yii::$app->session->set("area_gudang_{$pemesanan_id}", $areaGudangData);
                
                $transaction->commit();

                Yii::$app->session->setFlash('success', 'Pemesanan detail berhasil diperbarui.');
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

            // Berikan pesan berhasil hanya jika semua validasi sukses
            if ($isValid) {
                Yii::$app->session->setFlash('success', 'Pemesanan detail berhasil diperbarui.');
            }

            // Redirect setelah POST
            return $this->redirect(['view', 'pemesanan_id' => $modelPemesanan->pemesanan_id]);
        }

        return $this->render('update-qty', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
        ]);
    }

    /**
     * Deletes an existing Pemesanan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pemesanan_id Pemesanan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionCancel($pemesanan_id)
    {
        // Temukan data pemesanan yang sesuai
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);

        if ($modelPemesanan) {
            // Mulai transaksi untuk memastikan data konsisten
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Hapus semua detail terkait jika ada
                PesanDetail::deleteAll(['pemesanan_id' => $pemesanan_id]);

                // Hapus data pembelian terkait pemesanan
                Pembelian::deleteAll(['pemesanan_id' => $pemesanan_id]);

                // Hapus data pemesanan
                $modelPemesanan->delete();

                // Commit transaksi jika semua penghapusan berhasil
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Pemesanan dan pembelian terkait telah dibatalkan.');
            } catch (\Exception $e) {
                // Rollback transaksi jika ada kegagalan
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal membatalkan pemesanan: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Data pemesanan tidak ditemukan.');
        }

        // Redirect ke halaman utama atau halaman sebelumnya
        return $this->redirect(['index']);
    }
    public function actionDelete($pemesanan_id)
    {
        $this->findModel($pemesanan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Get BOM data untuk auto-fill form (AJAX)
     */
    public function actionGetBomData($permintaan_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $permintaan = \app\models\PermintaanPelanggan::findOne($permintaan_id);
            if (!$permintaan || $permintaan->tipe_pelanggan != 1) {
                return ['success' => false, 'message' => 'Hanya untuk Custom Order'];
            }
            
            $kodePermintaan = $permintaan->generateKodePermintaan();
            $permintaanDetails = \app\models\PermintaanDetail::find()
                ->where(['permintaan_id' => $permintaan_id])
                ->andWhere(['IS NOT', 'barang_custom_pelanggan_id', null])
                ->all();
            
            if (empty($permintaanDetails)) {
                return ['success' => false, 'message' => 'Tidak ada barang produksi'];
            }
            
            $bahanTotal = [];
            
            foreach ($permintaanDetails as $detail) {
                $bomCustoms = \app\models\BomCustom::find()
                    ->where(['barang_custom_pelanggan_id' => $detail->barang_custom_pelanggan_id])
                    ->all();
                
                foreach ($bomCustoms as $bomCustom) {
                    $barangId = $bomCustom->barang_id;
                    $totalQty = $bomCustom->qty_per_unit * $detail->qty_permintaan;
                    
                    if (isset($bahanTotal[$barangId])) {
                        $bahanTotal[$barangId]['qty'] += $totalQty;
                    } else {
                        $barang = \app\models\Barang::findOne($barangId);
                        $bahanTotal[$barangId] = [
                            'barang_id' => $barangId,
                            'kode_barang' => $barang->kode_barang,
                            'nama_barang' => $barang->nama_barang,
                            'qty' => $totalQty,
                            'catatan' => "Digunakan Untuk Permintaan : {$kodePermintaan}",
                            'langsung_pakai' => 1,
                        ];
                    }
                }
            }
            
            if (empty($bahanTotal)) {
                return ['success' => false, 'message' => 'Tidak ada BOM'];
            }
            
            return [
                'success' => true,
                'data' => array_values($bahanTotal),
                'kode_permintaan' => $kodePermintaan
            ];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get ROP data untuk auto-fill form (AJAX)
     */
    public function actionGetRopData($stock_rop_id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $stockRop = \app\models\StockRop::findOne($stock_rop_id);
            if (!$stockRop) {
                return ['success' => false, 'message' => 'Data Stock ROP tidak ditemukan'];
            }
            
            // Ambil data barang
            $barang = $stockRop->barang;
            if (!$barang) {
                return ['success' => false, 'message' => 'Barang tidak ditemukan'];
            }
            
            // Return data untuk auto-fill
            return [
                'success' => true,
                'data' => [[
                    'barang_id' => $barang->barang_id,
                    'kode_barang' => $barang->kode_barang,
                    'nama_barang' => $barang->nama_barang,
                    'qty' => $stockRop->jumlah_eoq, // Jumlah yang harus dipesan (EOQ)
                    'catatan' => "Pemesanan berdasarkan ROP periode " . $stockRop->getPeriodeFormatted() . " (Stock: " . $stockRop->stock_barang . ", ROP: " . $stockRop->jumlah_rop . ")",
                    'langsung_pakai' => 0, // Default tidak langsung pakai
                ]],
                'kode_rop' => 'ROP ID-' . str_pad($stock_rop_id, 3, '0', STR_PAD_LEFT)
            ];
            
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Finds the Pemesanan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pemesanan_id Pemesanan ID
     * @return Pemesanan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pemesanan_id)
    {
        if (($model = Pemesanan::findOne(['pemesanan_id' => $pemesanan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionGetUserInfo()
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'message' => 'User not logged in']);
        }

        // Mengambil data user yang sedang login    
        $user = Yii::$app->user->identity;

        return $this->asJson([
            'success' => true,
            'username' => $user->nama_pengguna,
        ]);
    }

    public function actionVerify($pemesanan_id)
    {
        // Cari model Pembelian berdasarkan ID
        $modelPembelian = Pemesanan::findOne($pemesanan_id);
        if (!$modelPembelian) {
            Yii::$app->session->setFlash('error', 'Data pemesanan tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Ambil semua pembelianDetail yang terkait dengan pembelian ini
        $pemesananDetails = PesanDetail::findAll(['pemesanan_id' => $pemesanan_id]);

        // Ambil area_gudang data dari session
        $areaGudangData = Yii::$app->session->get("area_gudang_{$pemesanan_id}", []);


        // Cek apakah semua is_correct == 1
        $allCorrect = true;
        foreach ($pemesananDetails as $detail) {
            if ($detail->is_correct != 1) {
                $allCorrect = false;
                break;
            }
        }

        if ($allCorrect) {
            // Jika semua is_correct == 1, ubah status pada tabel Pemesanan
            $modelPemesanan = Pemesanan::findOne($modelPembelian->pemesanan_id);
            if ($modelPemesanan) {
                $modelPemesanan->status = Pemesanan::STATUS_COMPLETE; // atau nilai status sesuai kebutuhan
                if ($modelPemesanan->save(false)) { // Simpan tanpa validasi

                    // Loop melalui setiap detail pemesanan untuk membuat data stok
                    foreach ($pemesananDetails as $detail) {
                        $gudang = new Gudang();
                        $gudang->tanggal = date('Y-m-d'); // Sesuaikan format tanggal jika diperlukan
                        $gudang->barang_id = $detail->barang_id; // Sesuaikan dengan barang_id terkait
                        $gudang->user_id = Yii::$app->user->id; // Mengambil ID user yang saat ini sedang login

                        if ($detail->langsung_pakai == 1) {
                            $gudang->quantity_awal = $this->getCurrentStock($detail->barang_id); // Dapatkan quantity awal
                            $gudang->quantity_masuk = $detail->qty_terima; // Sesuaikan dengan jumlah quantity masuk
                            $gudang->quantity_keluar = $detail->qty_terima; // Misalnya, tidak ada quantity keluar pada saat ini
                            $gudang->quantity_akhir = $gudang->quantity_awal + $gudang->quantity_masuk - $gudang->quantity_keluar;
                            $gudang->kode = 3; //area produksi

                            $stock = new Gudang();
                            $stock->tanggal = date('Y-m-d');
                            $stock->barang_id = $detail->barang_id;
                            $stock->user_id = Yii::$app->user->id;
                            $stock->quantity_awal = $this->getCurrentStockProduksi($detail->barang_id);
                            $stock->quantity_masuk = $detail->qty_terima; // Sesuaikan dengan jumlah quantity masuk
                            $stock->quantity_keluar = 0; // Misalnya, tidak ada quantity keluar pada saat ini
                            $stock->quantity_akhir = $stock->quantity_awal + $stock->quantity_masuk;
                            $stock->kode = 2;
                            $stock->area_gudang = 3; //area produksi
                            $stock->catatan = 'Barang Langsung Pakai';
                            $stock->created_at = date('Y-m-d H:i:s');
                            $stock->update_at = date('Y-m-d H:i:s');
                            if (!$stock->save(false)) {
                                Yii::$app->session->setFlash('error', 'Gagal menyimpan data stok Produksi untuk barang ID: ' . $detail->barang_id);
                            }
                        } else {
                            // Ambil area_gudang dari session, default Area 2 jika tidak ada
                            $areaGudang = isset($areaGudangData[$detail->pesandetail_id]) 
                                ? $areaGudangData[$detail->pesandetail_id] 
                                : 2; // Default Area 2 (bawah tangga)
                        
                            $gudang->quantity_awal = $this->getCurrentStock($detail->barang_id); // Dapatkan quantity awal
                            $gudang->quantity_masuk = $detail->qty_terima; // Sesuaikan dengan jumlah quantity masuk
                            $gudang->quantity_keluar = 0; // Sesuaikan dengan jumlah quantity masuk
                            $gudang->quantity_akhir = $gudang->quantity_awal + $gudang->quantity_masuk;
                            $gudang->kode = 1;
                            $gudang->area_gudang = $areaGudang;;
                        }
                        $gudang->catatan = 'Verifikasi pemesanan ID: ' . $pemesanan_id; // Catatan tambahan
                        $gudang->created_at = date('Y-m-d H:i:s');
                        $gudang->update_at = date('Y-m-d H:i:s');

                        if (!$gudang->save(false)) {
                            Yii::$app->session->setFlash('error', 'Gagal menyimpan data stok Gudang untuk barang ID: ' . $detail->barang_id);
                            return $this->redirect(['view', 'pemesanan_id' => $pemesanan_id]);
                        }
                    }

                    // UPDATE STATUS PERMINTAAN JADI "ON PROGRESS" (1)
                    if (!empty($modelPemesanan->permintaan_id)) {
                        $permintaan = \app\models\PermintaanPelanggan::findOne($modelPemesanan->permintaan_id);
                        if ($permintaan && $permintaan->status_permintaan == 0) {
                            $permintaan->status_permintaan = 1; // On Progress
                            $permintaan->save(false);
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Pemesanan berhasil diverifikasi dan stok berhasil diperbarui.');
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal mengupdate status pemesanan.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Data pemesanan tidak ditemukan.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Verifikasi gagal. Pastikan semua item sudah disetujui (is_correct == 1).');
        }

        // Kembali ke halaman pembelian
        return $this->redirect(['view', 'pemesanan_id' => $pemesanan_id]);
    }

    protected function getCurrentStock($barang_id)
    {
        $currentStock = Gudang::find()->where(['barang_id' => $barang_id])->orderBy(['created_at' => SORT_DESC])->one();
        return $currentStock ? $currentStock->quantity_akhir : 0; // Jika tidak ada stok sebelumnya, mulai dari 0
    }
    protected function getCurrentStockProduksi($barang_id)
    {
        $currentStock = Stock::find()->where(['barang_id' => $barang_id])->orderBy(['created_at' => SORT_DESC])->one();
        return $currentStock ? $currentStock->quantity_akhir : 0; // Jika tidak ada stok sebelumnya, mulai dari 0
    }

    /**
     * Helper function untuk mendapatkan supplier utama dari barang
     * Menggunakan JOIN untuk query 
     */
    protected function getSupplierUtama($barangId)
    {
        // Query langsung ke supplier_barang_detail
        $result = (new \yii\db\Query())
            ->select('sbd.supplier_id')
            ->from('supplier_barang sb')
            ->innerJoin('supplier_barang_detail sbd', 'sbd.supplier_barang_id = sb.supplier_barang_id')
            ->where(['sb.barang_id' => $barangId])
            ->andWhere(['sbd.supp_utama' => 1])
            ->one();
        
        if ($result) {
            return $result['supplier_id'];
        }
        
        // Fallback: Ambil supplier pertama jika tidak ada yang utama
        $fallback = (new \yii\db\Query())
            ->select('sbd.supplier_id')
            ->from('supplier_barang sb')
            ->innerJoin('supplier_barang_detail sbd', 'sbd.supplier_barang_id = sb.supplier_barang_id')
            ->where(['sb.barang_id' => $barangId])
            ->orderBy(['sbd.supplier_barang_detail_id' => SORT_ASC])
            ->limit(1)
            ->one();
        
        return $fallback ? $fallback['supplier_id'] : 0;
    }

    /**
     * Helper function untuk mendapatkan harga dari supplier tertentu
     * @param int $barangId
     * @param int $supplierId (optional) - jika null, ambil dari supplier utama
     */
    protected function getHargaSupplierUtama($barangId, $supplierId = null)
    {
        $query = (new \yii\db\Query())
            ->select('sbd.harga_per_kg')
            ->from('supplier_barang sb')
            ->innerJoin('supplier_barang_detail sbd', 'sbd.supplier_barang_id = sb.supplier_barang_id')
            ->where(['sb.barang_id' => $barangId]);
        
        if ($supplierId !== null) {
            // Ambil harga dari supplier tertentu
            $query->andWhere(['sbd.supplier_id' => $supplierId]);
        } else {
            // Ambil dari supplier utama
            $query->andWhere(['sbd.supp_utama' => 1]);
        }
        
        $result = $query->one();
        
        if ($result) {
            return $result['harga_per_kg'];
        }
        
        // Fallback: Ambil harga pertama jika tidak ada
        $fallback = (new \yii\db\Query())
            ->select('sbd.harga_per_kg')
            ->from('supplier_barang sb')
            ->innerJoin('supplier_barang_detail sbd', 'sbd.supplier_barang_id = sb.supplier_barang_id')
            ->where(['sb.barang_id' => $barangId])
            ->orderBy(['sbd.supplier_barang_detail_id' => SORT_ASC])
            ->limit(1)
            ->one();
        
        return $fallback ? $fallback['harga_per_kg'] : 0;
    }
}
