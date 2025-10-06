<?php

namespace app\controllers;

use app\models\Barang;
use app\models\Penggunaan;
use app\models\PenggunaanDetail;
use app\models\PenggunaanDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Gudang;
use app\models\Stock;
use yii\base\Model;
use Yii;

/**
 * PenggunaanDetailController implements the CRUD actions for PenggunaanDetail model.
 */
class PenggunaanDetailController extends Controller
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
     * Lists all PenggunaanDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PenggunaanDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PenggunaanDetail model.
     * @param int $gunadetail_id Gunadetail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($gunadetail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($gunadetail_id),
        ]);
    }

    /**
     * Creates a new PenggunaanDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $penggunaanId = Yii::$app->request->get('temporaryGunaId');
        if (!$penggunaanId) {
            return $this->actionCreatePenggunaan();
        }
        $penggunaan = Penggunaan::findOne(['penggunaan_id' => $penggunaanId]);

        $modelDetails = new PenggunaanDetail();

        if (Yii::$app->request->isPost) {
            $modelDetails = ModelHelper::createMultiple(PenggunaanDetail::classname());
            Yii::debug("Hasil createMultiple: " . json_encode($modelDetails), __METHOD__);

            if (Model::loadMultiple($modelDetails, YII::$app->request->post())) {
                if (Model::validateMultiple($modelDetails)) {
                        foreach ($modelDetails as $index => $model) {
                            echo "<pre>Item #$index errors: " . print_r($model->getErrors(), true) . "</pre>";
                        }
                        exit;

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($modelDetails as $index => $model) {
                            if (!model instanceof PenggunaanDetail) {
                                throw new \Exception("Elemen {$index} bukan instance dari PesanDetail.");
                            }
                            $model->penggunaan_id = $penggunaanId;
                            $model->created_at = date('Y-m-d H:i:s');

                            // Set default values jika diperlukan
                            $model->status_penggunaan = $model->status_penggunaan ?? 0;
                            
                            if (!$model->save()) {
                                
                                // Debug validation errors
                                var_dump($model->errors);
                                var_dump($model->attributes);
                                
                                Yii::$app->session->setFlash('error', 'Penyimpanan gagal untuk model ke-' . $index);
                                throw new \Exception('Gagal menyimpan detail penggunaan: ' . json_encode($model->getErrors()));
                            }
                        }

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'Semua data berhasil disimpan.');

                        // Langsung redirect ke view-by-order (tanpa create tabel lain)
                        return $this->redirect(['view-by-order', 'penggunaan_id' => $penggunaanId]);
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                        Yii::debug($e->getMessage(), __METHOD__);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');
                    foreach ($modelDetails as $index => $model) {
                            Yii::info("Model #$index: " . json_encode($model->attributes), 'debug');
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
            'penggunaan' => $penggunaan,
        ]);
    }

    /**
     * Fungsi untuk membuat penggunaan (mirip seperti pemesanan)
     */
    public function actionCreatePenggunaan()
    {
        // Buat penggunaan baru
        $penggunaan = new Penggunaan();
        $penggunaan->user_id = Yii::$app->user->id;
        $penggunaan->tanggal = date('Y-m-d H:i:s');
        $penggunaan->total_item_penggunaan = 0; // Set awal total item

        // Simpan penggunaan dan cek apakah berhasil
        if ($penggunaan->save()) {
            // Simpan penggunaan_id di sesi untuk digunakan pada detail
            Yii::$app->session->set('temporaryGunaId', $penggunaan->penggunaan_id);
            Yii::debug("Penggunaan berhasil dibuat dengan ID: " . $penggunaan->penggunaan_id, __METHOD__);

            // Langsung redirect ke create detail (tanpa create tabel lain)
            return $this->redirect(['create']);
        } else {
            // Log kesalahan
            Yii::error("Gagal membuat penggunaan: " . json_encode($penggunaan->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat penggunaan.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Updates an existing PenggunaanDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $gunadetail_id Gunadetail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($gunadetail_id)
    {
        $model = $this->findModel($gunadetail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'gunadetail_id' => $model->gunadetail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PenggunaanDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $gunadetail_id Gunadetail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($gunadetail_id)
    {
        $this->findModel($gunadetail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PenggunaanDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $gunadetail_id Gunadetail ID
     * @return PenggunaanDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($gunadetail_id)
    {
        if (($model = PenggunaanDetail::findOne(['gunadetail_id' => $gunadetail_id])) !== null) {
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
            // Yii::debug("Search initiated with query parameter: " . $q);
            Yii::error("Search method called with q: " . $q, __METHOD__);

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

    public function actionTestSearch()
    {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    
    try {
        $count = Barang::find()->count();
        return ['status' => 'OK', 'barang_count' => $count];
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
    }
    
}
