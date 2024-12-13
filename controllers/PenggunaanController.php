<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Gudang;
use app\models\Stock;
use Yii;
use app\models\Penggunaan;
use app\models\PenggunaanSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PenggunaanController implements the CRUD actions for Penggunaan model.
 */
class PenggunaanController extends BaseController
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
     * Lists all Penggunaan models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PenggunaanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Penggunaan model.
     * @param int $penggunaan_id Penggunaan ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($penggunaan_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($penggunaan_id),
        ]);
    }

    /**
     * Creates a new Penggunaan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelPenggunaans = [new Penggunaan()];

        if (Yii::$app->request->isPost) {
            // Load multiple instances
            $modelPenggunaans = ModelHelper::createMultiple(Penggunaan::classname());
            if (Model::loadMultiple($modelPenggunaans, Yii::$app->request->post())) {
                foreach ($modelPenggunaans as $index => $modelPenggunaan) {
                    Yii::info("Loaded Model Penggunaan #$index: " . json_encode($modelPenggunaan->attributes), 'modelData');
                }
            } else {
                Yii::info("Data failed to load into modelPenggunaans.", 'loadError');
            }

            // Validate models
            if (Model::validateMultiple($modelPenggunaans)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelPenggunaans as $index => $modelPenggunaan) {
                        $modelPenggunaan->tanggal_digunakan = date('Y-m-d');
                        if (!$modelPenggunaan->save()) {
                            Yii::$app->session->setFlash('error', "Failed to save item #{$index}: " . json_encode($modelPenggunaan->getErrors()));
                            throw new \yii\db\Exception('Failed to save items.');
                        }
                        $gudang = new Gudang();
                        $gudang->tanggal = date('Y-m-d'); // Sesuaikan format tanggal jika diperlukan
                        $gudang->barang_id = $modelPenggunaan->barang_id; // Sesuaikan dengan barang_id terkait
                        $gudang->user_id = $modelPenggunaan->user_id;
                        $gudang->quantity_awal = $this->getCurrentStockGudang($modelPenggunaan->barang_id); // Dapatkan quantity awal
                        $gudang->quantity_masuk = 0; // Sesuaikan dengan jumlah quantity masuk
                        $gudang->quantity_keluar = $modelPenggunaan->jumlah_digunakan; // Misalnya, tidak ada quantity keluar pada saat ini
                        $gudang->quantity_akhir = $gudang->quantity_awal + $gudang->quantity_masuk - $gudang->quantity_keluar;
                        $gudang->catatan = $modelPenggunaan->catatan;
                        if (!$gudang->save(false)) {
                            Yii::$app->session->setFlash('error', 'Gagal menyimpan data stok untuk barang ID: ' . $modelPenggunaan->barang_id);
                        }

                        $stock = new Stock();
                        $stock->tambah_stock = date('Y-m-d'); // Sesuaikan format tanggal jika diperlukan
                        $stock->barang_id = $gudang->barang_id; // Sesuaikan dengan barang_id terkait
                        $stock->user_id = $gudang->user_id;
                        $stock->quantity_awal = $this->getCurrentStock($modelPenggunaan->barang_id); // Dapatkan quantity awal
                        $stock->quantity_masuk = $gudang->quantity_keluar; // Sesuaikan dengan jumlah quantity masuk
                        $stock->quantity_keluar = 0; // Misalnya, tidak ada quantity keluar pada saat ini
                        $stock->quantity_akhir = $stock->quantity_awal + $stock->quantity_masuk - $stock->quantity_keluar;
                        if (!$stock->save(false)) {
                            Yii::$app->session->setFlash('error', 'Gagal menyimpan data stok untuk barang ID: ' . $modelPenggunaan->barang_id);
                        }
                    }

                    // Commit transaction jika semua data berhasil disimpan
                    $transaction->commit();

                    // Setel flash message sukses jika transaksi berhasil
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');

                    // Redirect ke halaman detail penggunaan setelah berhasil menyimpan
                    Yii::$app->session->set('modelPenggunaans', $modelPenggunaans);
                    return $this->redirect(['index']);
                } catch (\yii\db\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Database error: ' . $e->getMessage());
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                }
            } else {
                $allErrors = [];
                foreach ($modelPenggunaans as $index => $modelPenggunaan) {
                    $errors = $modelPenggunaan->getErrors();
                    if (!empty($errors)) {
                        $allErrors[] = "Item #{$index} errors: " . json_encode($errors);
                    }
                }
                Yii::$app->session->setFlash('error', 'Validation failed: ' . implode(' | ', $allErrors));
            }
        }

        return $this->render('create', [
            'modelPenggunaans' => $modelPenggunaans,
            'isReadonly' => true,
        ]);
    }


    /**
     * Updates an existing Penggunaan model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $penggunaan_id Penggunaan ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($penggunaan_id)
    {
        $model = $this->findModel($penggunaan_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'penggunaan_id' => $model->penggunaan_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Penggunaan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $penggunaan_id Penggunaan ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($penggunaan_id)
    {
        $this->findModel($penggunaan_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Penggunaan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $penggunaan_id Penggunaan ID
     * @return Penggunaan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($penggunaan_id)
    {
        if (($model = Penggunaan::findOne(['penggunaan_id' => $penggunaan_id])) !== null) {
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

    protected function getCurrentStock($barang_id)
    {
        $currentStock = Stock::find()->where(['barang_id' => $barang_id])->orderBy(['created_at' => SORT_DESC])->one();
        return $currentStock ? $currentStock->quantity_akhir : 0; // Jika tidak ada stok sebelumnya, mulai dari 0
    }
    protected function getCurrentStockGudang($barang_id)
    {
        $currentStock = Gudang::find()->where(['barang_id' => $barang_id])->orderBy(['created_at' => SORT_DESC])->one();
        return $currentStock ? $currentStock->quantity_akhir : 0; // Jika tidak ada stok sebelumnya, mulai dari 0
    }
    public function actionGetStock($barang_id)
    {
        $gudang = Gudang::find()
            ->joinWith('barang') // Asumsi ada relasi ke tabel barang jika perlu
            ->where(['barang.barang_id' => $barang_id])
            ->orderBy(['gudang.id_gudang' => SORT_DESC])
            ->one();

        return \yii\helpers\Json::encode(['quantity_akhir' => $gudang ? $gudang->quantity_akhir : 0]);
    }
}
