<?php

namespace app\controllers;

use app\models\Pembelian;
use app\models\PembelianDetail;
use app\models\PembelianSearch;
use app\models\Pemesanan;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PembelianController implements the CRUD actions for Pembelian model.
 */
class PembelianController extends Controller
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
     * Lists all Pembelian models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PembelianSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Pembelian model.
     * @param int $pembelian_id Pembelian ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($pembelian_id)
    {
        $model = $this->findModel($pembelian_id);

        // Mengambil semua PesanDetail yang terkait dengan pemesanan ini
        $PembelianDetail = $model->pembelianDetails;

        // Mengirim model dan pesanDetails ke view
        return $this->render('view', [
            'model' => $model,
            'PembelianDetail' => $PembelianDetail, // Pastikan $pesanDetails diteruskan
        ]);
    }

    /**
     * Creates a new Pembelian model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Pembelian();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'pembelian_id' => $model->pembelian_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Pembelian model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $pembelian_id Pembelian ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($pembelian_id)
    {
        // Memuat model Pembelian berdasarkan ID
        $modelPembelian = Pembelian::findOne($pembelian_id);
        if (!$modelPembelian) {
            throw new NotFoundHttpException("Data pembelian tidak ditemukan.");
        }

        // Memuat semua detail yang terkait dengan pembelian ini
        $modelDetails = PembelianDetail::findAll(['pembelian_id' => $pembelian_id]);

        // Jika form disubmit
        if (Yii::$app->request->isPost) {
            $detailsData = Yii::$app->request->post('PembelianDetail', []);

            // Iterasi dan simpan setiap PembelianDetail
            foreach ($detailsData as $id => $attributes) {
                $detailModel = PembelianDetail::findOne($id); // Temukan detail berdasarkan ID
                if ($detailModel) {
                    // Update atribut sesuai input dari form
                    $detailModel->cek_barang = $attributes['cek_barang'] ?? $detailModel->cek_barang;
                    $detailModel->total_biaya = $attributes['total_biaya'] ?? $detailModel->total_biaya;
                    $detailModel->catatan = $attributes['catatan'] ?? $detailModel->catatan;
                    $detailModel->is_correct = isset($attributes['is_correct']) ? 1 : 0;

                    // Simpan setiap detail model jika valid
                    if ($detailModel->validate()) {
                        $detailModel->save(false);
                    } else {
                        Yii::$app->session->setFlash('error', "Error pada detail ID {$id}: " . json_encode($detailModel->getErrors()));
                    }
                } else {
                    Yii::$app->session->setFlash('error', "Detail pembelian dengan ID {$id} tidak ditemukan.");
                }
            }

            $totalBiaya = PembelianDetail::find()
                ->where(['pembelian_id' => $pembelian_id])
                ->sum('total_biaya');

            // Simpan total ke kolom total_biaya di tabel pembelian
            $modelPembelian->total_biaya = $totalBiaya;
            $modelPembelian->save(false);



            Yii::$app->session->setFlash('success', 'Pembelian detail berhasil diperbarui.');
            return $this->redirect(['view', 'pembelian_id' => $modelPembelian->pembelian_id]);
        }

        return $this->render('update', [
            'modelPembelian' => $modelPembelian,
            'modelDetails' => $modelDetails,
        ]);
    }
    public function actionVerify($pembelian_id)
    {
        // Cari model Pembelian berdasarkan ID
        $modelPembelian = Pembelian::findOne($pembelian_id);
        if (!$modelPembelian) {
            Yii::$app->session->setFlash('error', 'Data pembelian tidak ditemukan.');
            return $this->redirect(['index']);
        }

        // Ambil semua pembelianDetail yang terkait dengan pembelian ini
        $pembelianDetails = PembelianDetail::findAll(['pembelian_id' => $pembelian_id]);

        // Cek apakah semua is_correct == 1
        $allCorrect = true;
        foreach ($pembelianDetails as $detail) {
            if ($detail->is_correct != 1) {
                $allCorrect = false;
                break;
            }
        }

        if ($allCorrect) {
            // Jika semua is_correct == 1, ubah status pada tabel Pemesanan
            $modelPemesanan = Pemesanan::findOne($modelPembelian->pemesanan_id);
            if ($modelPemesanan) {
                $modelPemesanan->status = Pemesanan::STATUS_VERIFIED; // atau nilai status sesuai kebutuhan
                if ($modelPemesanan->save(false)) { // Simpan tanpa validasi
                    Yii::$app->session->setFlash('success', 'Pemesanan berhasil diverifikasi.');
                } else {
                    Yii::$app->session->setFlash('error', 'Gagal mengupdate status pemesanan.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Data pemesanan tidak ditemukan.');
            }
        } else {
            Yii::$app->session->setFlash('warning', 'Verifikasi gagal. Pastikan semua item sudah disetujui (is_correct == 1).');
        }

        // Kembali ke halaman pembelian
        return $this->redirect(['view', 'pembelian_id' => $pembelian_id]);
    }




    /**
     * Deletes an existing Pembelian model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $pembelian_id Pembelian ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($pembelian_id)
    {
        $this->findModel($pembelian_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pembelian model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $pembelian_id Pembelian ID
     * @return Pembelian the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($pembelian_id)
    {
        if (($model = Pembelian::findOne(['pembelian_id' => $pembelian_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
