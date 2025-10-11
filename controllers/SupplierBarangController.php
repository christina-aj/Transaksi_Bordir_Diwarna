<?php

namespace app\controllers;

use app\models\SupplierBarang;
use app\models\SupplierBarangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierBarangController implements the CRUD actions for SupplierBarang model.
 */
class SupplierBarangController extends Controller
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
     * Lists all SupplierBarang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierBarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SupplierBarang model.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($supplier_barang_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($supplier_barang_id),
        ]);
    }

    /**
     * Creates a new SupplierBarang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new SupplierBarang();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'supplier_barang_id' => $model->supplier_barang_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SupplierBarang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($supplier_barang_id)
    {
        $model = $this->findModel($supplier_barang_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'supplier_barang_id' => $model->supplier_barang_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SupplierBarang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($supplier_barang_id)
    {
        $this->findModel($supplier_barang_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SupplierBarang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $supplier_barang_id Supplier Barang ID
     * @return SupplierBarang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($supplier_barang_id)
    {
        if (($model = SupplierBarang::findOne(['supplier_barang_id' => $supplier_barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
