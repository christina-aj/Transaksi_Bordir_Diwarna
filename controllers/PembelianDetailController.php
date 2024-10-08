<?php

namespace app\controllers;

use app\models\PembelianDetail;
use app\models\PembelianDetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PembelianDetailController implements the CRUD actions for PembelianDetail model.
 */
class PembelianDetailController extends Controller
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
     * Lists all PembelianDetail models.
     *
     * @return string
     */
    public function actionIndex1()
    {
        $searchModel = new PembelianDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'showFullContent' => true,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionIndex2()
    {
        $searchModel = new PembelianDetailSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'showFullContent' => false,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PembelianDetail model.
     * @param int $belidetail_id Belidetail ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($belidetail_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($belidetail_id),
        ]);
    }

    /**
     * Creates a new PembelianDetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PembelianDetail();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'belidetail_id' => $model->belidetail_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PembelianDetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $belidetail_id Belidetail ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($belidetail_id)
    {
        $model = $this->findModel($belidetail_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'belidetail_id' => $model->belidetail_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PembelianDetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $belidetail_id Belidetail ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($belidetail_id)
    {
        $this->findModel($belidetail_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PembelianDetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $belidetail_id Belidetail ID
     * @return PembelianDetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($belidetail_id)
    {
        if (($model = PembelianDetail::findOne(['belidetail_id' => $belidetail_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
