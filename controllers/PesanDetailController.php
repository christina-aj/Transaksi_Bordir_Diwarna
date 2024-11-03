<?php

namespace app\controllers;

use app\models\Barang;
use app\models\Pemesanan;
use app\models\Pembelian;
use app\models\PembelianDetail;
use app\models\PesanDetail;
use app\models\PesanDetailSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\helpers\ModelHelper;
use app\models\Gudang;
use app\models\Stock;
use yii\base\Model;

/**
 * PesanDetailController implements the CRUD actions for PesanDetail model.
 */
class PesanDetailController extends Controller
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
            ]
        );
    }

    /**
     * Lists all PesanDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PesanDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PesanDetail model.
     * @param int $pesandetail_id Pesandetail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pesandetail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($pesandetail_id),
        ]);
    }

    /**
     * Creates a new PesanDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($pembelianId)
    {
        $pemesananId = Yii::$app->session->get('temporaryOrderId');
        if (!$pemesananId) {
            return $this->actionCreatePemesanan();
        }
        $pemesanan = Pemesanan::findOne(['pemesanan_id' => $pemesananId]);

        $modelDetails = [new PesanDetail()]; // Awal dengan satu instance

        if (Yii::$app->request->post()) {
            $modelDetails = ModelHelper::createMultiple(PesanDetail::classname());
            Yii::debug("Hasil createMultiple: " . json_encode($modelDetails), __METHOD__);

            if (Model::loadMultiple($modelDetails, Yii::$app->request->post())) {
                if (Model::validateMultiple($modelDetails)) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($modelDetails as $index => $model) {
                            if (!$model instanceof PesanDetail) {
                                throw new \Exception("Elemen {$index} bukan instance dari PesanDetail.");
                            }
                            $model->pemesanan_id = $pemesananId;
                            $model->created_at = date('Y-m-d H:i:s');
                            $model->langsung_pakai = !empty(Yii::$app->request->post('PesanDetail')[$index]['langsung_pakai']) ? 1 : 0;
                            $model->is_correct = !empty(Yii::$app->request->post('PesanDetail')[$index]['is_correct']) ? 1 : 0;
                            if (!$model->save()) {
                                Yii::$app->session->setFlash('error', 'Penyimpanan gagal untuk model ke-' . $index);
                                throw new \Exception('Gagal menyimpan detail pemesanan: ' . json_encode($model->getErrors()));
                            }
                        }
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Semua data berhasil disimpan.');

                        if (!empty($modelDetails) && isset($modelDetails[0]->pesandetail_id)) {
                            return $this->actionCreatePembelianDetail($pembelianId, $modelDetails, $pemesananId);
                        } else {
                            throw new \Exception('Tidak ada data di modelDetails.');
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                        Yii::debug($e->getMessage(), __METHOD__);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');
                    foreach ($modelDetails as $index => $model) {
                        if ($model->hasErrors()) {
                            Yii::debug("Model ke-{$index} gagal divalidasi: " . json_encode($model->getErrors()), __METHOD__);
                        }
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', 'Data gagal dimuat, silakan coba lagi.');
                Yii::debug("Data POST gagal dimuat: " . json_encode(Yii::$app->request->post()), __METHOD__);
            }
        }

        return $this->render('create', [
            'modelDetail' => $modelDetails,
            'pemesanan' => $pemesanan,
        ]);
    }

    // Fungsi untuk membuat pemesanan
    public function actionCreatePemesanan()
    {
        // Buat pemesanan baru
        $pemesanan = new Pemesanan();
        $pemesanan->user_id = Yii::$app->user->id; // Ambil ID pengguna
        $pemesanan->tanggal = date('Y-m-d H:i:s');
        $pemesanan->total_item = 0; // Set awal total item

        // Simpan pemesanan dan cek apakah berhasil
        if ($pemesanan->save()) {
            // Simpan pemesanan_id di sesi untuk digunakan pada detail
            Yii::$app->session->set('temporaryOrderId', $pemesanan->pemesanan_id);
            Yii::debug("Pemesanan berhasil dibuat dengan ID: " . $pemesanan->pemesanan_id, __METHOD__);

            // Redirect ke create untuk membuat detail pemesanan
            return $this->actionCreatePembelian();
        } else {
            // Log kesalahan
            Yii::error("Gagal membuat pemesanan: " . json_encode($pemesanan->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pemesanan.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    // Fungsi untuk membuat pembelian dan pembelian detail
    public function actionCreatePembelian()
    {
        $pemesananId = Yii::$app->session->get('temporaryOrderId');
        // Buat pembelian baru
        $pembelian = new Pembelian();
        $pembelian->pemesanan_id = $pemesananId; // Mengaitkan dengan pemesanan
        $pembelian->user_id = null;
        $pembelian->total_biaya = 0; // Set total biaya ke 0

        // Simpan pembelian dan cek apakah berhasil
        if ($pembelian->save()) {
            Yii::debug("Pembelian berhasil dibuat dengan ID: " . $pembelian->pembelian_id, __METHOD__);

            // Setelah pembelian dibuat, buat juga pembelian detail
            return $this->redirect(['create', 'pembelianId' => $pembelian->pembelian_id]);
        } else {
            // Log kesalahan
            Yii::error("Gagal membuat pembelian: " . json_encode($pembelian->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    // Fungsi untuk membuat pembelian detail
    public function actionCreatePembelianDetail($pembelianId, $modelDetails, $pemesananId)
    {
        // Pastikan bahwa $modelDetails adalah array
        if (!is_array($modelDetails)) {
            Yii::$app->session->setFlash('error', 'Parameter modelDetails tidak valid.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($modelDetails as $pesanDetail) {
                // Periksa apakah $pesanDetail adalah instance dari PesanDetail
                if (!$pesanDetail instanceof PesanDetail) {
                    Yii::$app->session->setFlash('error', 'Objek pesan detail tidak valid.');
                    throw new \Exception('Objek dalam modelDetails bukan instance dari PesanDetail.');
                }

                // Buat pembelian detail baru untuk setiap pesan detail
                $pembelianDetail = new PembelianDetail();
                $pembelianDetail->pembelian_id = $pembelianId;

                // Gunakan nama properti yang sesuai (ganti 'id' dengan 'pesandetail_id')
                $pembelianDetail->pesandetail_id = $pesanDetail->pesandetail_id; // Asosiasi dengan ID pesan detail

                $pembelianDetail->cek_barang = 0;
                $pembelianDetail->total_biaya = 0;
                $pembelianDetail->is_correct = 0;
                $pembelianDetail->created_at = date('Y-m-d H:i:s');

                // Simpan pembelian detail dan cek hasilnya
                if (!$pembelianDetail->save()) {
                    Yii::$app->session->setFlash('error', 'Gagal membuat pembelian detail.');
                    throw new \Exception('Gagal menyimpan pembelian detail: ' . json_encode($pembelianDetail->getErrors()));
                }

                Yii::debug("Pembelian detail berhasil dibuat dengan ID: " . $pembelianDetail->pesandetail_id, __METHOD__);
            }

            // Commit transaksi jika semua data berhasil disimpan
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Semua pembelian detail berhasil disimpan.');

            // Redirect ke tampilan pembelian detail
            return $this->redirect(['view-by-order', 'pemesanan_id' => $pemesananId]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            Yii::debug($e->getMessage(), __METHOD__);

            return $this->redirect(['index']);
        }
    }


    public function actionUpdate($pesandetail_id)
    {
        // Fetch the PesanDetail model along with its 'barang' relation
        $modelDetail = PesanDetail::find()->joinWith(['barang', 'pemesanan'])->where(['pesandetail_id' => $pesandetail_id])->one();

        if (!$modelDetail) {

            Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
            return $this->redirect(['index']);
        }
        // Store pemesanan_id for use in the view
        $pemesananId = $modelDetail->pemesanan_id;

        // Handle form submission
        if (Yii::$app->request->post()) {
            // Create multiple models dynamically using a helper function
            $modelsDetail = ModelHelper::createMultiple(PesanDetail::classname(), [$modelDetail], 'pesandetail_id');

            // Load and validate multiple models
            if (Model::loadMultiple($modelsDetail, Yii::$app->request->post()) && Model::validateMultiple($modelsDetail)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelsDetail as $index => $model) {
                        // Set required values like update_at timestamp
                        $model->update_at = date('Y-m-d H:i:s');

                        // Update nama_barang if barang_id is set
                        if ($model->barang_id) {
                            $barang = Barang::findOne($model->barang_id);
                            if ($barang) {
                                $model->nama_barang = $barang->nama_barang;
                            }
                        }

                        // Save the model and handle errors
                        if (!$model->save()) {
                            $errorDetails = json_encode($model->getErrors());
                            Yii::$app->session->setFlash('error', 'Gagal memperbarui data untuk model ke-' . $index);
                            Yii::error("Gagal menyimpan detail pemesanan untuk model ke-{$index}: {$errorDetails}", __METHOD__);
                            throw new \Exception('Gagal menyimpan detail pemesanan: ' . $errorDetails);
                        }

                        if ($model->barang_id) {
                            if ($model->langsung_pakai == 0) {
                                $gudang = Gudang::find()->where(['barang_id' => $model->barang_id])
                                    ->orderBy(['barang_id' => SORT_DESC]) // Asumsi id berurutan sebagai acuan data terakhir
                                    ->one();
                                if ($gudang) {
                                    $gudang->tanggal = date('Y-m-d');
                                    $gudang->user_id = 1;
                                    $gudang->quantity_awal = $gudang->quantity_akhir;
                                    if (empty($model->qty_terima)) {
                                        $gudang->quantity_masuk += $model->qty_terima;
                                    } else {
                                        $gudang->quantity_masuk += $model->qty_terima;
                                    }

                                    $gudang->quantity_keluar = 0;
                                    $gudang->quantity_akhir = $gudang->quantity_awal + $gudang->quantity_masuk;
                                } else {
                                    $gudang = new Gudang();
                                    $gudang->barang_id = $model->barang_id;
                                    $gudang->tanggal = date('Y-m-d');
                                    $gudang->user_id = 1;
                                    $gudang->quantity_awal = 0;
                                    $gudang->quantity_masuk += $model->qty_terima;
                                    $gudang->quantity_keluar = 0;
                                    $gudang->quantity_akhir = $gudang->quantity_awal;
                                }
                                if (!$gudang->save()) {
                                    $errorDetails = json_encode($gudang->getErrors());
                                    Yii::$app->session->setFlash('error', 'Gagal memperbarui stok Gudang untuk barang.');
                                    Yii::error("Gagal menyimpan stok Gudang: {$errorDetails}", __METHOD__);
                                    throw new \Exception('Gagal menyimpan stok Gudang: ' . $errorDetails);
                                }
                            } elseif ($model->langsung_pakai == 1) {

                                $qty_minta = $model->qty;
                                $qty_terima = $model->qty_terima;
                                // Cari data terakhir berdasarkan barang_id untuk mendapatkan quantity_awal
                                $lastStock = Stock::find()->joinWith('pesan_detail')
                                    ->where(['barang_id' => $model->barang_id, 'pesandetail_id' => $model->pesandetail_id])
                                    ->orderBy(['barang_id' => SORT_DESC, 'pesandetail_id' => SORT_DESC]) // Asumsi id berurutan sebagai acuan data terakhir
                                    ->one();

                                // Jika ada data sebelumnya, gunakan quantity_akhir sebagai quantity_awal baru
                                $quantityAwal = $lastStock ? $lastStock->quantity_akhir : 0;
                                $previousQtyTerima = $lastStock ? ($lastStock->quantity_masuk + $lastStock->quantity_keluar) : 0;

                                if ($qty_terima >= $qty_minta) {
                                    // Jika barang diterima sepenuhnya dalam satu kali penerimaan
                                    $quantityMasuk = $qty_minta;
                                } else {
                                    // Jika barang diterima sebagian dan ada tambahan penerimaan
                                    $quantityMasuk = $qty_terima - $previousQtyTerima;
                                }

                                $quantityMasuk = max($quantityMasuk, 0);
                                // Buat instance Stock baru
                                $stock = new Stock();
                                $stock->barang_id = $model->barang_id;
                                $stock->tambah_stock = date('Y-m-d');
                                $stock->user_id = 1;
                                $stock->quantity_awal = $quantityAwal;
                                $stock->quantity_masuk = $quantityMasuk;
                                $stock->quantity_keluar = 0;
                                $stock->quantity_akhir = $quantityAwal + $quantityMasuk;
                                $stock->is_ready = 1;
                                $stock->is_new = 0;
                                $stock->created_at = date('Y-m-d');
                                $stock->updated_at = date('Y-m-d');
                                if (!$stock->save()) {
                                    $errorDetails = json_encode($stock->getErrors());
                                    Yii::$app->session->setFlash('error', 'Gagal memperbarui stok produksi untuk barang.');
                                    Yii::error("Gagal menyimpan stok produksi: {$errorDetails}", __METHOD__);
                                    throw new \Exception('Gagal menyimpan stok produksi: ' . $errorDetails);
                                }
                            }
                        }
                    }

                    // Commit transaction if all saves are successful
                    $transaction->commit();

                    // Clear temporary order ID and show success message
                    Yii::$app->session->remove('temporaryOrderId');
                    Yii::$app->session->setFlash('success', 'Data berhasil diperbarui.');
                    return $this->redirect(['view', 'pesandetail_id' => $pesandetail_id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                    Yii::error('Transaksi gagal: ' . $e->getMessage(), __METHOD__);
                }
            } else {
                // Handle validation failure
                Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');
                $this->logValidationErrors($modelsDetail);
            }
        }

        // Render view with model details
        return $this->render('update', [
            'modelDetail' => [$modelDetail],
            'pemesananId' => $pemesananId,
        ]);
    }

    /**
     * Logs validation errors for models.
     * 
     * @param array $models Array of models with potential validation errors.
     */
    private function logValidationErrors($models)
    {
        foreach ($models as $index => $model) {
            if ($model->hasErrors()) {
                $errorDetails = json_encode($model->getErrors());
                Yii::error("Model ke-{$index} gagal divalidasi: {$errorDetails}", __METHOD__);
            }
        }
    }

    public function actionUpdateMultiple($pemesanan_id)
    {
        // Ambil semua PesanDetail terkait berdasarkan pemesanan_id
        $model = Pemesanan::findOne(['pemesanan_id' => $pemesanan_id]);
        $modelsDetail = PesanDetail::findAll(['pemesanan_id' => $pemesanan_id]);

        if (empty($modelsDetail)) {
            Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Jika form di-submit
        if (Yii::$app->request->post()) {
            // Buat array model baru untuk menangani form dinamis
            $modelsDetail = ModelHelper::createMultiple(PesanDetail::classname(), $modelsDetail, 'pesandetail_id');

            // Load multiple data dari form ke dalam array model
            if (Model::loadMultiple($modelsDetail, Yii::$app->request->post()) && Model::validateMultiple($modelsDetail)) {
                // Mulai transaksi
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelsDetail as $model) {
                        $model->update_at = date('Y-m-d H:i:s'); // Set waktu update

                        if (!$model->save(false)) {
                            Yii::$app->session->setFlash('error', 'Gagal memperbarui data untuk beberapa model.');
                            throw new \Exception('Gagal menyimpan detail pemesanan: ' . json_encode($model->getErrors()));
                        }
                    }

                    // Jika semua penyimpanan berhasil, commit transaksi
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Semua data berhasil diperbarui.');
                    return $this->redirect(['pemesanan/view', 'pemesanan_id' => $pemesanan_id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                    Yii::debug($e->getMessage(), __METHOD__);
                }
            } else {
                // Jika validasi gagal, tampilkan pesan error dan debug log
                Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');
            }
        }

        // Render view untuk multiple update
        return $this->render('update-multiple', [
            'modelsDetail' => $modelsDetail,
            'pemesananId' => $pemesanan_id,
            'model' => $model,
        ]);
    }










    /**
     * Deletes an existing PesanDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pesandetail_id Pesandetail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pesandetail_id)
    {
        $this->findModel($pesandetail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PesanDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pesandetail_id Pesandetail ID
     * @return PesanDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pesandetail_id)
    {
        if (($model = PesanDetail::findOne(['pesandetail_id' => $pesandetail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function actionSearch($q = null, $is_search_form = false)
    {
        // Set the response format to JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            // Log the incoming query parameter
            Yii::debug("Search initiated with query parameter: " . $q);

            // Make sure you receive the query parameter
            if (empty($q)) {
                Yii::warning("Query parameter is missing.");
                throw new \yii\web\BadRequestHttpException('Query parameter is missing');
            }

            // Perform the query
            Yii::debug("Performing search query for: " . $q);
            $items = Barang::find()
                ->select(['barang_id', 'kode_barang', 'nama_barang', 'angka', 'warna', 'unit.satuan'])
                ->leftJoin('unit', 'barang.unit_id = unit.unit_id')
                ->where(['like', 'barang_id', $q])
                ->orWhere(['like', 'nama_barang', $q])
                ->orWhere(['like', 'kode_barang', $q])
                ->orWhere(['like', 'angka', $q])
                ->orWhere(['like', 'warna', $q])
                ->orWhere(['like', 'unit.satuan', $q])
                ->limit(10)
                ->asArray()
                ->all();

            // Log the result of the query
            Yii::debug("Query result: " . json_encode($items));

            // Check if any items were found
            if (empty($items)) {
                // Return a list with one item to avoid 'undefined' in typeahead
                return [
                    [
                        'id' => null,
                        'barang_id' => null,
                        'kode_barang' => null,
                        'nama_barang' => 'Barang tidak ditemukan',
                        'angka' => null,
                        'satuan' => null,
                        'warna' => null,
                        'value' => 'Barang tidak ditemukan'
                    ]
                ];
            }

            // Prepare the response array
            $result = [];
            foreach ($items as $item) {
                $result[] = [
                    'id' => $item['barang_id'],
                    'barang_id' => $item['barang_id'],
                    'kode_barang' => $item['kode_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'angka' => $item['angka'],
                    'satuan' => $item['satuan'],
                    'warna' => $item['warna'],
                    // Conditional value based on whether it's a search form or not
                    'value' => $is_search_form ? $item['nama_barang'] : $item['kode_barang'] . " - " . $item['nama_barang'] . " - " . $item['angka'] . " - " . $item['satuan']
                ];
            }

            // Log the final result to be returned
            Yii::debug("Final search result: " . json_encode($result));

            // Return the result as JSON
            return $result;
        } catch (\yii\web\HttpException $e) {
            // Log the HttpException
            Yii::error("HttpException occurred: " . $e->getMessage());
            // Return an HTTP exception with the message
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            // Log the general exception
            Yii::error("Error in search: " . $e->getMessage());
            throw new \yii\web\ServerErrorHttpException('Internal server error');
        }
    }





    public function actionViewByOrder($pemesanan_id)
    {
        // Dapatkan semua pesan detail berdasarkan pemesanan_id
        $models = PesanDetail::find()->where(['pemesanan_id' => $pemesanan_id])->all();

        return $this->render('view_by_order', [
            'models' => $models,
            'pemesanan_id' => $pemesanan_id,
        ]);
    }
}
