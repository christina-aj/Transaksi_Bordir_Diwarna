<?php

namespace app\controllers;

use app\models\SupplierBarangDetail;
use app\models\SupplierBarangDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierBarangDetailController implements the CRUD actions for SupplierBarangDetail model.
 */
class SupplierBarangDetailController extends Controller
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
     * Lists all SupplierBarangDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierBarangDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierBarangDetail model.
     * @param int $supplier_barang_detail_id Supplier Barang Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($supplier_barang_detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($supplier_barang_detail_id),
        ]);
    }

    /**
     * Creates a new SupplierBarangDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SupplierBarangDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'supplier_barang_detail_id' => $model->supplier_barang_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SupplierBarangDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $supplier_barang_detail_id Supplier Barang Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($supplier_barang_detail_id)
    {
        $model = $this->findModel($supplier_barang_detail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'supplier_barang_detail_id' => $model->supplier_barang_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SupplierBarangDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $supplier_barang_detail_id Supplier Barang Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($supplier_barang_detail_id)
    {
        $this->findModel($supplier_barang_detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SupplierBarangDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $supplier_barang_detail_id Supplier Barang Detail ID
     * @return SupplierBarangDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($supplier_barang_detail_id)
    {
        if (($model = SupplierBarangDetail::findOne(['supplier_barang_detail_id' => $supplier_barang_detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
