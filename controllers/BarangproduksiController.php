<?php

namespace app\controllers;

use Yii;
use app\models\Barangproduksi;
use app\models\Barangproduksisearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BarangproduksiController implements the CRUD actions for Barangproduksi model.
 */
class BarangproduksiController extends BaseController
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

    /**
     * Lists all Barangproduksi models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new Barangproduksisearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Barangproduksi model.
     * @param int $barang_produksi_id Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($barang_produksi_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($barang_produksi_id),
        ]);
    }

    /**
     * Creates a new Barangproduksi model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Barangproduksi();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'barang_produksi_id' => $model->barang_produksi_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Barangproduksi model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $barang_produksi_id Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($barang_produksi_id)
    {
        $model = $this->findModel($barang_produksi_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'barang_produksi_id' => $model->barang_produksi_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Barangproduksi model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $barang_produksi_id Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($barang_produksi_id)
    {
        $model = $this->findModel($barang_produksi_id);

        // Periksa apakah tabel laporanproduksi ada
        $db = Yii::$app->db;
        $tableSchema = $db->getSchema()->getTableSchema('laporanproduksi');

        if ($tableSchema === null) {
            Yii::$app->session->setFlash('error', 'Tabel laporan produksi tidak ditemukan. Hubungi administrator.');
            return $this->redirect(['index']);
        }

        $isBarangUsedInLaporanProduksi = (new \yii\db\Query())
            ->from('laporanproduksi')
            ->where(['nama_barang' => $barang_produksi_id]) 
            ->exists();

        if ($isBarangUsedInLaporanProduksi) {
            Yii::$app->session->setFlash('error', 'Barang ini tidak dapat dihapus karena sedang digunakan di laporan produksi.');
            return $this->redirect(['index']);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Barang berhasil dihapus.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Barangproduksi model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $barang_produksi_id Barang ID
     * @return Barangproduksi the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($barang_produksi_id)
    {
        if (($model = Barangproduksi::findOne(['barang_produksi_id' => $barang_produksi_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
