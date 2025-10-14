<?php

namespace app\controllers;

use app\models\PermintaanDetail;
use app\models\PermintaanDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PermintaanDetailController implements the CRUD actions for PermintaanDetail model.
 */
class PermintaanDetailController extends Controller
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
     * Lists all PermintaanDetail models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PermintaanDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PermintaanDetail model.
     * @param int $permintaan_detail_id Permintaan Detail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($permintaan_detail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($permintaan_detail_id),
        ]);
    }

    /**
     * Creates a new PermintaanDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PermintaanDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'permintaan_detail_id' => $model->permintaan_detail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PermintaanDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $permintaan_detail_id Permintaan Detail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($permintaan_detail_id)
    {
        $model = $this->findModel($permintaan_detail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'permintaan_detail_id' => $model->permintaan_detail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PermintaanDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $permintaan_detail_id Permintaan Detail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($permintaan_detail_id)
    {
        $this->findModel($permintaan_detail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PermintaanDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $permintaan_detail_id Permintaan Detail ID
     * @return PermintaanDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($permintaan_detail_id)
    {
        if (($model = PermintaanDetail::findOne(['permintaan_detail_id' => $permintaan_detail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
