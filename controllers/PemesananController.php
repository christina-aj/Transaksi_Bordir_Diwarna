<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Pemesanan;
use app\models\PemesananSearch;
use app\models\PesanDetail;
use app\models\User;
use Yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        // Temukan model Pemesanan berdasarkan pemesanan_id
        $model = $this->findModel($pemesanan_id);

        // Mengambil semua PesanDetail yang terkait dengan pemesanan ini
        $pesanDetails = $model->pesanDetails;

        // Mengirim model dan pesanDetails ke view
        return $this->render('view', [
            'model' => $model,
            'pesanDetails' => $pesanDetails, // Pastikan $pesanDetails diteruskan
        ]);
    }

    /**
     * Creates a new Pemesanan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelPemesanan = new Pemesanan();
        $modelPemesanan->tanggal = date('Y-m-d');
        $modelPemesanan->user_id = Yii::$app->user->identity->user_id;
        $modelPemesanan->total_item = 0;

        // Retrieve the user's name
        $user = User::findOne($modelPemesanan->user_id);
        $modelPemesanan->nama_pemesan = $user->nama_pengguna;

        // Generate a temporary order code
        $kodeSementara = Pemesanan::find()->max('pemesanan_id') + 1;
        $modelPemesanan->kode_pemesanan = 'FPB-' . str_pad($kodeSementara, 3, '0', STR_PAD_LEFT);

        if ($modelPemesanan->save()) {
            // Redirect to the add details page, passing the pemesanan_id
            return $this->redirect(['add-details', 'pemesanan_id' => $modelPemesanan->pemesanan_id]);
        } else {
            Yii::$app->session->setFlash('error', 'Gagal membuat pemesanan.');
            return $this->redirect(['create']);
        }
    }

    public function actionAddDetails($pemesanan_id)
    {
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

        // Initialize an empty PesanDetail model
        $modelDetails = [new PesanDetail()];

        if (Yii::$app->request->isPost) {
            $modelDetails = ModelHelper::createMultiple(PesanDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            if (Model::validateMultiple($modelDetails)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelDetails as $index => $modelDetail) {
                        $modelDetail->pemesanan_id = $pemesanan_id;
                        $modelDetail->created_at = date('Y-m-d H:i:s');
                        $modelDetail->langsung_pakai = !empty(Yii::$app->request->post('PesanDetail')[$index]['langsung_pakai']) ? 1 : 0;
                        $modelDetail->is_correct = !empty(Yii::$app->request->post('PesanDetail')[$index]['is_correct']) ? 1 : 0;

                        if (!$modelDetail->save()) {
                            Yii::$app->session->setFlash('error', "Gagal menyimpan detail pemesanan ke-{$index}");
                            throw new \Exception('Gagal menyimpan detail pemesanan: ' . json_encode($modelDetail->getErrors()));
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Semua detail berhasil disimpan.');
                    return $this->redirect(['view', 'pemesanan_id' => $pemesanan_id]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Terjadi kesalahan: ' . $e->getMessage());
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validasi gagal, periksa input Anda.');
            }
        }

        return $this->render('create', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
            'isReadonly' => true,
        ]);
    }

    public function actionCreateDetail() {}



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
    public function actionGetUserInfo()
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['success' => false, 'message' => 'User not logged in']);
        }

        // Mengambil data user yang sedang login    
        $user = Yii::$app->user->identity;

        return $this->asJson([
            'success' => true,
            'username' => $user->nama_pengguna,
        ]);
    }
}
