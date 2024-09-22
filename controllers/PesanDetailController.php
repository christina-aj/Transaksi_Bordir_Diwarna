<?php

namespace app\controllers;

use app\models\Barang;
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
    public function actionCreate()
    {
        $model = new PesanDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'pesandetail_id' => $model->pesandetail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
