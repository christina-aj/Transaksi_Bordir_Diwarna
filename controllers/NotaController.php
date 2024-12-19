<?php

namespace app\controllers;

use app\models\Barangproduksi;
use app\models\Nota;
use app\models\Notasearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use  yii\helpers\ArrayHelper;
use Yii;

/**
 * NotaController implements the CRUD actions for Nota model.
 */
class NotaController extends BaseController
{
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
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['delete', 'update', 'create', 'index', 'view'], // Aksi yang diatur
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // Hanya pengguna yang sudah login
                        ],
                    ],
                ]
            ]
        );
    }

    public function actionIndex()
    {
        $searchModel = new Notasearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($nota_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($nota_id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Nota();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'nota_id' => $model->nota_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($nota_id)
    {
        $model = $this->findModel($nota_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'nota_id' => $model->nota_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($nota_id)
    {
        $this->findModel($nota_id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($nota_id)
    {
        if (($model = Nota::findOne(['nota_id' => $nota_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrint($nota_id)
    {
        $model = $this->findModel($nota_id);

        // Render the print view
        return $this->render('print', [
            'model' => $model,
        ]);
    }
}
