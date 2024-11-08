<?php

namespace app\controllers;

use app\models\Mesin;
use app\models\Mesinsearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MesinController implements the CRUD actions for Mesin model.
 */
class MesinController extends Controller
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
     * Lists all Mesin models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Mesinsearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mesin model.
     * @param int $mesin_id Mesin ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($mesin_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($mesin_id),
        ]);
    }

    /**
     * Creates a new Mesin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Mesin();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'mesin_id' => $model->mesin_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mesin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $mesin_id Mesin ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($mesin_id)
    {
        $model = $this->findModel($mesin_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'mesin_id' => $model->mesin_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mesin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $mesin_id Mesin ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($mesin_id)
    {
        $this->findModel($mesin_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mesin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $mesin_id Mesin ID
     * @return Mesin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($mesin_id)
    {
        if (($model = Mesin::findOne(['mesin_id' => $mesin_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
