<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\Pembelian;
use app\models\PembelianDetail;
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
            return $this->redirect(['add-details', 'pemesanan_id' => $pemesananId, 'pembelian_id' => $pembelian->pembelian_id]);
        } else {
            Yii::error("Gagal membuat pembelian: " . json_encode($pembelian->getErrors()), __METHOD__);
            Yii::$app->session->setFlash('error', 'Gagal membuat pembelian.');
            return $this->redirect(['index']); // Redirect ke halaman lain jika gagal
        }
    }

    public function actionAddDetails($pemesanan_id, $pembelian_id)
    {
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

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

                    // Simpan modelDetails ke session
                    Yii::$app->session->set('modelDetails', $modelDetails);

                    return $this->redirect(['create-pembelian-detail', 'pembelianId' => $pembelian_id, 'pemesananId' => $pemesanan_id]);
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

    public function actionCreatePembelianDetail($pembelianId, $pemesananId)
    {
        // Ambil modelDetails dari session
        $modelDetails = Yii::$app->session->get('modelDetails');
        if (!$modelDetails || !is_array($modelDetails)) {
            Yii::$app->session->setFlash('error', 'Data modelDetails tidak valid atau tidak ditemukan.');
            return $this->redirect(['index']);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($modelDetails as $pesanDetail) {
                if (!$pesanDetail instanceof PesanDetail) {
                    Yii::$app->session->setFlash('error', 'Objek pesan detail tidak valid.');
                    throw new \Exception('Objek dalam modelDetails bukan instance dari PesanDetail.');
                }

                $pembelianDetail = new PembelianDetail();
                $pembelianDetail->pembelian_id = $pembelianId;
                $pembelianDetail->pesandetail_id = $pesanDetail->pesandetail_id;
                $pembelianDetail->cek_barang = 0;
                $pembelianDetail->total_biaya = 0;
                $pembelianDetail->is_correct = 0;
                $pembelianDetail->created_at = date('Y-m-d H:i:s');

                if (!$pembelianDetail->save()) {
                    Yii::$app->session->setFlash('error', 'Gagal membuat pembelian detail.');
                    throw new \Exception('Gagal menyimpan pembelian detail: ' . json_encode($pembelianDetail->getErrors()));
                }
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Semua pembelian detail berhasil disimpan.');

            // Hapus modelDetails dari session setelah selesai
            Yii::$app->session->remove('modelDetails');

            return $this->redirect(['view', 'pemesanan_id' => $pemesananId]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            return $this->redirect(['index']);
        }
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
        $modelPemesanan = Pemesanan::findOne($pemesanan_id);
        if (!$modelPemesanan) {
            throw new NotFoundHttpException("Data pemesanan tidak ditemukan.");
        }

        // Ambil detail pemesanan yang terkait
        $modelDetails = PesanDetail::findAll(['pemesanan_id' => $modelPemesanan->pemesanan_id]);

        // Set nama_barang untuk setiap modelDetail jika dalam mode update
        foreach ($modelDetails as $modelDetail) {
            $modelDetail->nama_barang = $modelDetail->getNamaBarang();
        }

        if (Yii::$app->request->isPost) {
            $modelPemesanan->load(Yii::$app->request->post());

            // Ambil data detail dari POST
            $detailData = Yii::$app->request->post('PesanDetail', []);
            $isValid = $modelPemesanan->validate();

            // Loop untuk menyimpan atau memperbarui detail yang ada
            foreach ($modelDetails as $index => $modelDetail) {
                if (isset($detailData[$index])) {
                    $modelDetail->setAttributes($detailData[$index]);
                    $isValid = $modelDetail->validate() && $isValid;
                }
            }

            // Loop untuk menambah detail baru jika ada data baru
            $newDetails = [];
            foreach ($detailData as $index => $data) {
                if (!isset($modelDetails[$index])) { // Detail baru
                    $newDetail = new PesanDetail();
                    $newDetail->pemesanan_id = $modelPemesanan->pemesanan_id;
                    $newDetail->setAttributes($data);
                    $newDetails[] = $newDetail;
                    $isValid = $newDetail->validate() && $isValid;
                }
            }

            // Hapus PesanDetail yang dihapus oleh user beserta PembelianDetail yang terkait
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $deletedDetails = json_decode(Yii::$app->request->post('deleteRows', '[]'), true);
                if (is_array($deletedDetails)) {
                    foreach ($deletedDetails as $deletedId) {
                        $deletedDetail = PesanDetail::findOne($deletedId);
                        if ($deletedDetail) {
                            // Hapus semua PembelianDetail terkait dengan pesandetail_id ini
                            PembelianDetail::deleteAll(['pesandetail_id' => $deletedDetail->pesandetail_id]);

                            // Hapus PesanDetail
                            $deletedDetail->delete();
                        }
                    }
                }
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error("Gagal menghapus data: " . $e->getMessage());
            }

            // Jika valid, simpan perubahan di Pemesanan dan detailnya
            if ($isValid) {
                $modelPemesanan->save(false); // Simpan tanpa validasi karena sudah divalidasi di atas

                // Simpan detail yang diubah saja tanpa sinkronisasi ke PembelianDetail yang telah dihapus
                foreach ($modelDetails as $modelDetail) {
                    $modelDetail->save(false); // Simpan tanpa validasi
                }

                // Simpan detail baru dan tambahkan PembelianDetail baru jika ada detail baru
                $pembelian = Pembelian::findOne(['pemesanan_id' => $modelPemesanan->pemesanan_id]);
                foreach ($newDetails as $newDetail) {
                    if ($newDetail->save(false)) { // Simpan PesanDetail terlebih dahulu tanpa validasi
                        // Buat PembelianDetail baru untuk setiap PesanDetail yang baru disimpan
                        $pembelianDetail = new PembelianDetail([
                            'pembelian_id' => $pembelian->pembelian_id,
                            'pesandetail_id' => $newDetail->pesandetail_id, // Gunakan pesandetail_id dari PesanDetail yang baru disimpan
                            'cek_barang' => 0,
                            'total_biaya' => 0,
                            'is_correct' => 0,
                        ]);
                        $pembelianDetail->save(false); // Simpan PembelianDetail tanpa validasi
                    }
                }

                Yii::$app->session->setFlash('success', 'Pemesanan dan detail pembelian berhasil diperbarui.');
                return $this->redirect(['view', 'pemesanan_id' => $modelPemesanan->pemesanan_id]);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal memperbarui data pemesanan. Harap periksa kembali data yang dimasukkan.');
            }
        }

        return $this->render('update', [
            'modelPemesanan' => $modelPemesanan,
            'modelDetails' => $modelDetails,
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
