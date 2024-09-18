<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Pemesanan;
use app\models\PemesananSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;



/**
 * PemesananController implements the CRUD actions for Pemesanan model.
 */
class PemesananController extends Controller
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
     * Lists all Pemesanan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PemesananSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pemesanan model.
     * @param int $pemesanan_id Pemesanan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pemesanan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($pemesanan_id),
        ]);
    }

    public function actionCreateMultiple()
    {
        // Inisialisasi array pemesanan
        $pemesanan = [new Pemesanan()];

        if (Yii::$app->request->post()) {
            // Load data dari form post dan buat beberapa instance Pemesanan
            $pemesanan = ModelHelper::createMultiple(Pemesanan::class);
            Model::loadMultiple($pemesanan, Yii::$app->request->post());

            // Validasi model pemesanan
            $valid = Model::validateMultiple($pemesanan);

            if ($valid) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($pemesanan as $item) {
                        if (!$item->save(false)) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                    $transaction->commit();
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create-multiple', [
            'pemesanan' => $pemesanan, // Kirim variabel pemesanan ke view
        ]);
    }

    /**
     * Creates a new Pemesanan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */


    public function actionCreate()
    {
        $model = new Pemesanan();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'pemesanan_id' => $model->pemesanan_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pemesanan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pemesanan_id Pemesanan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pemesanan_id)
    {
        $model = $this->findModel($pemesanan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'pemesanan_id' => $model->pemesanan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Pemesanan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pemesanan_id Pemesanan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pemesanan_id)
    {
        $this->findModel($pemesanan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pemesanan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pemesanan_id Pemesanan ID
     * @return Pemesanan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pemesanan_id)
    {
        if (($model = Pemesanan::findOne(['pemesanan_id' => $pemesanan_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
