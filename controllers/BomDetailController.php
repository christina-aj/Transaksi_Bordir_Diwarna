<?php

namespace app\controllers;

use app\models\Barang;
use app\models\BomDetail;
use app\models\BomDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * BomDetailController implements the CRUD actions for BomDetail model.
 */
class BomDetailController extends Controller
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
     * Lists all BomDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BomDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BomDetail model.
     * @param int $BOM_detail_id Bom Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($BOM_detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($BOM_detail_id),
        ]);
    }

    /**
     * Creates a new BomDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BomDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'BOM_detail_id' => $model->BOM_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BomDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $BOM_detail_id Bom Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($BOM_detail_id)
    {
        $model = $this->findModel($BOM_detail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BOM_detail_id' => $model->BOM_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BomDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $BOM_detail_id Bom Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($BOM_detail_id)
    {
        $this->findModel($BOM_detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Search action untuk typeahead autocomplete
     * Digunakan untuk mencari barang bahan baku
     * 
     * @param string|null $q Query string untuk pencarian
     * @param bool $is_search_form Flag untuk format output berbeda
     * @return array JSON response dengan data barang
     */
    public function actionSearch($q = null, $is_search_form = false)
    {
        // Set response format ke JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            // Log query untuk debugging
            Yii::error("BomDetail Search called with q: " . $q, __METHOD__);

            // Validasi query parameter
            if (empty($q)) {
                Yii::warning("Query parameter is missing.");
                throw new \yii\web\BadRequestHttpException('Query parameter is missing');
            }

            // Query barang dari database
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

            // Log hasil query
            Yii::debug("Query result: " . json_encode($items));

            // Jika tidak ada hasil
            if (empty($items)) {
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

            // Format hasil untuk typeahead
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
                    // Format value sesuai kebutuhan
                    'value' => $is_search_form 
                        ? $item['nama_barang'] 
                        : $item['kode_barang'] . " - " . $item['nama_barang'] . 
                          ($item['angka'] ? " - " . $item['angka'] : "") . 
                          ($item['satuan'] ? " - " . $item['satuan'] : "")
                ];
            }

            // Log hasil akhir
            Yii::debug("Final search result: " . json_encode($result));

            return $result;

        } catch (\yii\web\HttpException $e) {
            Yii::error("HttpException occurred: " . $e->getMessage());
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            Yii::error("Error in search: " . $e->getMessage());
            throw new \yii\web\ServerErrorHttpException('Internal server error');
        }
    }

    /**
     * Test action untuk memastikan koneksi database dan model bekerja
     * @return array
     */
    public function actionTestSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        try {
            $count = Barang::find()->count();
            $sample = Barang::find()->limit(5)->asArray()->all();
            
            return [
                'status' => 'OK', 
                'barang_count' => $count,
                'sample_data' => $sample
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Finds the BomDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $BOM_detail_id Bom Detail ID
     * @return BomDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BOM_detail_id)
    {
        if (($model = BomDetail::findOne(['BOM_detail_id' => $BOM_detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}