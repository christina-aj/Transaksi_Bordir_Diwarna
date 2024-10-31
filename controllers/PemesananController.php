<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\Pembelian;
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

        // Set default data
        $modelPemesanan->tanggal = date('Y-m-d');
        $modelPemesanan->user_id = Yii::$app->user->identity->user_id;
        $modelPemesanan->total_item = 0;
        $modelPemesanan->status = Pemesanan::STATUS_PENDING;
        $modelPemesanan->created_at = date('Y-m-d H:i:s');
        $modelPemesanan->updated_at = date('Y-m-d H:i:s');

        // Retrieve the user's name
        $user = User::findOne($modelPemesanan->user_id);
        $modelPemesanan->nama_pemesan = $user->nama_pengguna;

        // Generate a temporary order code
        $kodeSementara = Pemesanan::find()->max('pemesanan_id') + 1;
        $modelPemesanan->kode_pemesanan = 'FPB-' . str_pad($kodeSementara, 3, '0', STR_PAD_LEFT);

        // Simpan data `Pemesanan` dan lanjutkan ke pembuatan pembelian
        if ($modelPemesanan->save()) {
            // Set session untuk ID Pemesanan agar bisa digunakan di `actionCreatePembelian`
            Yii::$app->session->set('temporaryOrderId', $modelPemesanan->pemesanan_id);

            // Redirect ke `CreatePembelian`
            return $this->redirect(['create-pembelian']);
        } else {
            Yii::$app->session->setFlash('error', 'Gagal membuat pemesanan.');
            return $this->redirect(['index']);
        }
    }

    public function actionCreatePembelian()
    {
        // Ambil ID Pemesanan dari session
        $pemesananId = Yii::$app->session->get('temporaryOrderId');
        if (!$pemesananId) {
            Yii::$app->session->setFlash('error', 'ID pemesanan tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Buat pembelian baru
        $pembelian = new Pembelian();
        $pembelian->pemesanan_id = $pemesananId; // Mengaitkan dengan pemesanan
        $pembelian->user_id = Yii::$app->user->identity->user_id;
        $pembelian->total_biaya = 0; // Set total biaya ke 0

        // Simpan pembelian dan cek apakah berhasil
        if ($pembelian->save()) {
            Yii::debug("Pembelian berhasil dibuat dengan ID: " . $pembelian->pembelian_id, __METHOD__);

            // Redirect ke `AddDetails` untuk menambahkan detail pesanan
            return $this->redirect(['add-details', 'pemesanan_id' => $pemesananId]);
        } else {
            Yii::error("Gagal membuat pembelian: " . json_encode($pembelian->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    public function actionAddDetails($pemesanan_id)
    {
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

        // Inisialisasi model detail
        $modelDetails = [new PesanDetail()];


        if (Yii::$app->request->isPost) {
            $modelDetails = ModelHelper::createMultiple(PesanDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            if (Model::validateMultiple($modelDetails)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelDetails as $index => $modelDetail) {
                        $barang = Barang::findOne($modelDetail->barang_id);
                        $modelDetail->kode_pemesanan = $barang->kode_barang;
                        $modelDetail->pemesanan_id = $pemesanan_id;
                        $modelDetail->created_at = date('Y-m-d H:i:s');
                        $modelDetail->langsung_pakai = !empty(Yii::$app->request->post('PesanDetail')[$index]['langsung_pakai']) ? 1 : 0;
                        $modelDetail->is_correct = !empty(Yii::$app->request->post('PesanDetail')[$index]['is_correct']) ? 1 : 0;

                        if (!$modelDetail->save()) {
                            Yii::$app->session->setFlash('error', "Gagal menyimpan detail pemesanan ke-{$index}: " . json_encode($modelDetail->getErrors()));
                            throw new \Exception('Gagal menyimpan detail pemesanan.');
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
                Yii::$app->session->setFlash('error', 'Validasi gagal: ' . json_encode($modelDetails[0]->getErrors()));
            }
        }

        return $this->render('create', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
            'isReadonly' => true,
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

    public function actionCancel($pemesanan_id)
    {
        // Temukan data pemesanan yang sesuai
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);

        if ($modelPemesanan) {
            // Mulai transaksi untuk memastikan data konsisten
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Hapus semua detail terkait jika ada
                PesanDetail::deleteAll(['pemesanan_id' => $pemesanan_id]);

                // Hapus data pembelian terkait pemesanan
                Pembelian::deleteAll(['pemesanan_id' => $pemesanan_id]);

                // Hapus data pemesanan
                $modelPemesanan->delete();

                // Commit transaksi jika semua penghapusan berhasil
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Pemesanan dan pembelian terkait telah dibatalkan.');
            } catch (\Exception $e) {
                // Rollback transaksi jika ada kegagalan
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Gagal membatalkan pemesanan: ' . $e->getMessage());
            }
        } else {
            Yii::$app->session->setFlash('error', 'Data pemesanan tidak ditemukan.');
        }

        // Redirect ke halaman utama atau halaman sebelumnya
        return $this->redirect(['index']);
    }
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
