<?php

namespace app\controllers;

use app\models\BomBarang;
use app\models\BomBarangSearch;
use app\models\BomDetail;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;

/**
 * BomBarangController implements the CRUD actions for BomBarang model.
 */
class BomBarangController extends Controller
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
     * Lists all BomBarang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BomBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BomBarang model.
     * @param int $BOM_barang_id Bom Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($BOM_barang_id)
    {
        $model = $this->findModel($BOM_barang_id);
        $bomDetails = $model->bomDetails;

        return $this->render('view', [
            'model' => $model,
            'bomDetails' => $bomDetails,
        ]);
    }

    /**
     * Creates a new BomBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BomBarang();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'BOM_barang_id' => $model->BOM_barang_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BomBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $BOM_barang_id Bom Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($BOM_barang_id)
    {
        $model = $this->findModel($BOM_barang_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BOM_barang_id' => $model->BOM_barang_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BomBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $BOM_barang_id Bom Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($BOM_barang_id)
    {
        $this->findModel($BOM_barang_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BomBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $BOM_barang_id Bom Barang ID
     * @return BomBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BOM_barang_id)
    {
        if (($model = BomBarang::findOne(['BOM_barang_id' => $BOM_barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
