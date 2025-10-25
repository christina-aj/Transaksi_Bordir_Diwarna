<?php

namespace app\controllers;

use app\models\BomCustom;
use app\models\BomCustomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BomCustomController implements the CRUD actions for BomCustom model.
 */
class BomCustomController extends Controller
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
     * Lists all BomCustom models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BomCustomSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BomCustom model.
     * @param int $BOM_custom_id Bom Custom ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($BOM_custom_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($BOM_custom_id),
        ]);
    }

    /**
     * Creates a new BomCustom model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new BomCustom();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'BOM_custom_id' => $model->BOM_custom_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BomCustom model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $BOM_custom_id Bom Custom ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($BOM_custom_id)
    {
        $model = $this->findModel($BOM_custom_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'BOM_custom_id' => $model->BOM_custom_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BomCustom model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $BOM_custom_id Bom Custom ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($BOM_custom_id)
    {
        $this->findModel($BOM_custom_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BomCustom model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $BOM_custom_id Bom Custom ID
     * @return BomCustom the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($BOM_custom_id)
    {
        if (($model = BomCustom::findOne(['BOM_custom_id' => $BOM_custom_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
