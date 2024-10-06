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
        // Cek apakah pemesanan sudah ada di sesi
        $pemesananId = Yii::$app->session->get('temporaryOrderId');

        // Jika tidak ada pemesanan_id, buat pemesanan baru
        if (!$pemesananId) {
            return $this->actionCreatePemesanan();
        }

        // Inisialisasi array untuk beberapa model PesanDetail
        $modelDetails = [new PesanDetail()]; // Awal dengan satu instance

        // Jika form di-submit
        if (Yii::$app->request->post()) {
            // Menggunakan loadMultiple untuk memuat beberapa model dari data POST
            $modelDetails = ModelHelper::createMultiple(PesanDetail::classname());

            // Load multiple data dari form ke dalam array model
            if (Model::loadMultiple($modelDetails, Yii::$app->request->post())) {
                // Lakukan validasi pada semua model dalam array
                if (Model::validateMultiple($modelDetails)) {
                    // Mulai transaksi untuk memastikan semua model disimpan atau tidak sama sekali
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($modelDetails as $index => $model) {
                            $model->pemesanan_id = $pemesananId; // Kaitkan dengan pemesanan
                            $model->created_at = date('Y-m-d H:i:s');
                            $model->langsung_pakai = !empty(Yii::$app->request->post('PesanDetail')[$index]['langsung_pakai']) ? 1 : 0;
                            $model->is_correct = !empty(Yii::$app->request->post('PesanDetail')[$index]['is_correct']) ? 1 : 0;

                            // Jika penyimpanan gagal, lemparkan Exception
                            if (!$model->save()) {
                                // Set error dan keluarkan exception untuk rollback
                                Yii::$app->session->setFlash('error', 'Penyimpanan gagal untuk model ke-' . $index);
                                throw new \Exception('Gagal menyimpan detail pemesanan: ' . json_encode($model->getErrors()));
                            }
                        }
                        // Commit transaksi jika semuanya berhasil disimpan
                        $transaction->commit();

                        // Berikan pesan success dan redirect
                        Yii::$app->session->setFlash('success', 'Semua data berhasil disimpan.');
                        return $this->actionCreatePembelianDetail($pembelianId, $modelDetails[0]->pesandetail_id, $pemesananId);
                    } catch (\Exception $e) {
                        // Rollback transaksi jika ada kegagalan
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                        Yii::debug($e->getMessage(), __METHOD__);
                    }
                } else {
                    // Jika validasi gagal, tampilkan pesan error
                    Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');

                    // Debugging untuk menampilkan semua error dari model
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

        // Render view untuk form detail
        return $this->render('create', [
            'modelDetail' => $modelDetails,
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
    public function actionCreatePembelianDetail($pembelianId, $pesandetailId, $pemesanan_id)
    {
        // Buat pembelian detail baru
        $pembelianDetail = new PembelianDetail();
        $pembelianDetail->pembelian_id = $pembelianId; // Mengaitkan dengan ID pembelian
        $pembelianDetail->pesandetail_id = $pesandetailId; // Mengaitkan dengan detail pemesanan
        $pembelianDetail->cek_barang = 0; // Atur cek_barang ke 0
        $pembelianDetail->total_biaya = 0; // Set total biaya ke 0
        $pembelianDetail->is_correct = 0; // Set is_correct ke 0
        $pembelianDetail->created_at = date('Y-m-d H:i:s'); // Atur waktu pembuatan

        // Simpan pembelian detail dan cek apakah berhasil
        if ($pembelianDetail->save()) {
            Yii::debug("Pembelian detail berhasil dibuat dengan ID: " . $pembelianDetail->pesandetail_id, __METHOD__);

            // Redirect ke tampilan pembelian detail
            return $this->redirect(['view-by-order', 'pemesanan_id' => $pemesanan_id]); // Pastikan parameter yang benar di sini
        } else {
            // Log kesalahan
            Yii::error("Gagal membuat pembelian detail: " . json_encode($pembelianDetail->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian detail.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    public function actionUpdate($pesandetail_id)
    {
        // Ambil semua PesanDetail terkait berdasarkan pesandetail_id
        $modelsDetail = PesanDetail::findAll(['pesandetail_id' => $pesandetail_id]);

        if (empty($modelsDetail)) {
            Yii::$app->session->setFlash('error', 'Data tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Ambil pemesanan_id dari salah satu model PesanDetail, misalnya model pertama
        $pemesananId = $modelsDetail[0]->pemesanan_id;

        // Jika form di-submit
        if (Yii::$app->request->post()) {
            // Buat array model baru untuk menangani form dinamis
            $modelsDetail = ModelHelper::createMultiple(PesanDetail::classname(), $modelsDetail, 'pesandetail_id');

            // Load multiple data dari form ke dalam array model
            if (Model::loadMultiple($modelsDetail, Yii::$app->request->post()) && Model::validateMultiple($modelsDetail)) {
                // Mulai transaksi untuk memastikan semua model disimpan atau tidak sama sekali
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelsDetail as $index => $model) {
                        // Set nilai yang diperlukan (misal: pemesanan_id dan lainnya)
                        $model->update_at = date('Y-m-d H:i:s'); // Set waktu update

                        // Jika penyimpanan gagal, rollback transaksi
                        if (!$model->save()) {
                            Yii::$app->session->setFlash('error', 'Gagal memperbarui data untuk model ke-' . $index);
                            throw new \Exception('Gagal menyimpan detail pemesanan: ' . json_encode($model->getErrors()));
                        }
                    }

                    // Jika semua penyimpanan berhasil, commit transaksi
                    $transaction->commit();

                    // Hapus session temporaryOrderId setelah update
                    Yii::$app->session->remove('temporaryOrderId');

                    // Berikan pesan sukses dan redirect ke view atau index
                    Yii::$app->session->setFlash('success', 'Semua data berhasil diperbarui.');
                    return $this->redirect(['view', 'pesandetail_id' => $pesandetail_id]);
                } catch (\Exception $e) {
                    // Jika ada kesalahan, rollback transaksi dan tampilkan pesan error
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                    Yii::debug($e->getMessage(), __METHOD__);
                }
            } else {
                // Jika validasi gagal, tampilkan pesan error dan debug log
                Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');

                // Debugging untuk menampilkan semua error dari model
                foreach ($modelsDetail as $index => $model) {
                    if ($model->hasErrors()) {
                        Yii::debug("Model ke-{$index} gagal divalidasi: " . json_encode($model->getErrors()), __METHOD__);
                    }
                }
            }
        }

        // Render view dengan array modelDetail
        return $this->render('update', [
            'modelDetail' => $modelsDetail,  // Array model PesanDetail
            'pemesananId' => $pemesananId,   // Kirim pemesanan_id ke view jika diperlukan
        ]);
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
                    'value' => $is_search_form ? $item['nama_barang'] : $item['barang_id']
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
