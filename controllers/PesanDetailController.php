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

        // Model untuk detail pemesanan
        $modelDetail = new PesanDetail();

        // Cek jika form detail disubmit
        if ($modelDetail->load(Yii::$app->request->post())) {
            // Mengaitkan detail dengan pemesanan
            $modelDetail->pemesanan_id = $pemesananId; // Mengaitkan detail dengan pemesanan
            $modelDetail->created_at = date('Y-m-d H:i:s');

            // Simpan detail pemesanan
            if ($modelDetail->save()) {
                // Panggil fungsi untuk membuat pembelian dan pembelian detail
                return $this->actionCreatePembelianDetail($pembelianId, $modelDetail->pesandetail_id);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan detail pemesanan: ' . json_encode($modelDetail->getErrors()));
            }
        }

        // Render view untuk form detail
        return $this->render('create', [
            'model' => $modelDetail,
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
    public function actionCreatePembelianDetail($pembelianId, $pesandetailId)
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
            Yii::debug("Pembelian detail berhasil dibuat dengan ID: " . $pembelianDetail->belidetail_id, __METHOD__);

            // Redirect ke tampilan pembelian detail
            return $this->redirect(['view', 'pesandetail_id' => $pesandetailId]); // Pastikan parameter yang benar di sini
        } else {
            // Log kesalahan
            Yii::error("Gagal membuat pembelian detail: " . json_encode($pembelianDetail->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian detail.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }




    /**
     * Updates an existing PesanDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pesandetail_id Pesandetail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pesandetail_id)
    {
        $model = $this->findModel($pesandetail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pesandetail_id' => $model->pesandetail_id]);
        }

        return $this->render('update', [
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
    public function actionSearch($q = null)
    {
        try {
            // Make sure you receive the query parameter
            if (empty($q)) {
                throw new \yii\web\BadRequestHttpException('Query parameter is missing');
            }

            // Perform the query
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

            // Check if any items were found
            if (empty($items)) {
                throw new NotFoundHttpException('No items found');
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
                    'value' => $item['barang_id']
                ];
            }

            return json_encode($result);
        } catch (\Exception $e) {
            // Log the error and return a 500 response with an error message
            Yii::error("Error in search: " . $e->getMessage());
            Yii::$app->response->statusCode = 500;
            return json_encode(['error' => $e->getMessage()]);
        }
    }
}
