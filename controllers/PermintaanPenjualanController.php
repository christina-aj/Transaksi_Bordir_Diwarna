<?php

namespace app\controllers;

use app\models\PermintaanPenjualan;
use app\models\PermintaanPenjualanSearch;
use app\models\DetailPermintaan;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;

/**
 * PermintaanPenjualanController implements the CRUD actions for PermintaanPenjualan model.
 */
class PermintaanPenjualanController extends Controller
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
     * Lists all PermintaanPenjualan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PermintaanPenjualanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermintaanPenjualan model.
     * @param int $permintaan_penjualan_id Permintaan Penjualan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($permintaan_penjualan_id)
    {
        $model = $this->findModel($permintaan_penjualan_id);
        $detailPermintaans = $model->detailPermintaans;
        
        return $this->render('view', [
            'model' => $model,
            'detailPermintaans' => $detailPermintaans,
        ]);
    }

    /**
     * Creates a new PermintaanPenjualan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PermintaanPenjualan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'permintaan_penjualan_id' => $model->permintaan_penjualan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PermintaanPenjualan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $permintaan_penjualan_id Permintaan Penjualan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($permintaan_penjualan_id)
    {
        $model = $this->findModel($permintaan_penjualan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'permintaan_penjualan_id' => $model->permintaan_penjualan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PermintaanPenjualan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $permintaan_penjualan_id Permintaan Penjualan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($permintaan_penjualan_id)
    {
        $this->findModel($permintaan_penjualan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermintaanPenjualan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $permintaan_penjualan_id Permintaan Penjualan ID
     * @return PermintaanPenjualan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permintaan_penjualan_id)
    {
        if (($model = PermintaanPenjualan::findOne(['permintaan_penjualan_id' => $permintaan_penjualan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
