<?php

namespace app\controllers;

use app\models\MasterPelanggan;
use app\models\MasterPelangganSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MasterPelangganController implements the CRUD actions for MasterPelanggan model.
 */
class MasterPelangganController extends Controller
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
     * Lists all MasterPelanggan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MasterPelangganSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    

    /**
     * Displays a single MasterPelanggan model.
     * @param int $pelanggan_id Pelanggan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionView($pelanggan_id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($pelanggan_id),
    //     ]);
    // }
    public function actionView($pelanggan_id)
    {
        // return $this->render('view', [
        //     'model' => $this->findModel($barang_custom_pelanggan_id),
        // ]);
        $model = $this->findModel($pelanggan_id);

        // Ambil semua barang custom yang terhubung dengan pelanggan ini
        $barangCustom = \app\models\BarangCustomPelanggan::find()
            ->where(['pelanggan_id' => $pelanggan_id])
            ->with(['bomCustoms.barang']) // biar relasi bomCustom dan barang ikut diambil
            ->all();
        
        // echo "Debug berhasil sampai sini<br>";
        // echo "<pre>"; print_r($barangCustom); echo "</pre>";
        // exit;
        // echo "<pre>";
        // print_r($barangCustom);
        // echo "</pre>";
        // exit;

        return $this->render('view', [
            'model' => $model,
            'barangCustom' => $barangCustom,
        ]);
    }

    /**
     * Creates a new MasterPelanggan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new MasterPelanggan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'pelanggan_id' => $model->pelanggan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MasterPelanggan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pelanggan_id Pelanggan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pelanggan_id)
    {
        $model = $this->findModel($pelanggan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pelanggan_id' => $model->pelanggan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MasterPelanggan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pelanggan_id Pelanggan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pelanggan_id)
    {
        $this->findModel($pelanggan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MasterPelanggan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pelanggan_id Pelanggan ID
     * @return MasterPelanggan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pelanggan_id)
    {
        if (($model = MasterPelanggan::findOne(['pelanggan_id' => $pelanggan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
