<?php

namespace app\controllers;

use app\models\Gudang;
use app\models\GudangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

/**
 * GudangController implements the CRUD actions for Gudang model.
 */
class GudangController extends Controller
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
     * Lists all Gudang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GudangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Gudang model.
     * @param int $id_gudang Id Gudang
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_gudang)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_gudang),
        ]);
    }

    /**
     * Creates a new Gudang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Gudang();


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id_gudang' => $model->id_gudang]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
        if ($model->save()) {
            // Data berhasil disimpan
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            // Handle error
            print_r($model->errors); // Cetak daftar error
            die();
        }
    }

    /**
     * Updates an existing Gudang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_gudang Id Gudang
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_gudang)
    {
        $model = $this->findModel($id_gudang);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_gudang' => $model->id_gudang]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Gudang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_gudang Id Gudang
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_gudang)
    {
        $this->findModel($id_gudang)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Gudang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_gudang Id Gudang
     * @return Gudang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_gudang)
    {
        if (($model = Gudang::findOne(['id_gudang' => $id_gudang])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetStock()
    {
        // Mendapatkan data POST dari request
        $barang_id = Yii::$app->request->post('barang_id');

        // Cek apakah barang_id ada dalam request
        if ($barang_id) {
            // Mencari stock terbaru berdasarkan barang_id
            $stock = Gudang::find()
                ->where(['barang_id' => $barang_id])
                ->orderBy(['id_gudang' => SORT_DESC]) // Mengambil stock terbaru
                ->one(); // Mengambil satu record terbaru

            // Jika ditemukan stock, kirimkan quantity_akhir sebagai respon JSON
            if ($stock) {
                return $this->asJson(['quantity_akhir' => $stock->quantity_akhir]);
            }
        }

        // Jika tidak ditemukan atau barang_id tidak valid, kembalikan null
        return $this->asJson(['quantity_akhir' => null]);
    }
}
