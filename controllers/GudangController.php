<?php

namespace app\controllers;

use app\models\Gudang;
use app\models\GudangSearch;
use app\models\MoveAreaForm;
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
     * Lists all Gudang models (only barang gudang - kode 1).
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GudangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, Gudang::KODE_BARANG_GUDANG);

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
     * Creates a new Gudang model (barang gudang - kode 1).
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Gudang();
        $model->kode = Gudang::KODE_BARANG_GUDANG; // Auto set kode untuk barang gudang
        $model->area_gudang = 1;
        
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                // Pastikan kode tetap 1
                $model->kode = Gudang::KODE_BARANG_GUDANG;
                
                // Set user_id jika belum ada
                if (empty($model->user_id)) {
                    $model->user_id = Yii::$app->user->id;
                }
                
                // Hitung quantity akhir berdasarkan stock sebelumnya
                $currentStock = Gudang::getCurrentStock($model->barang_id, Gudang::KODE_BARANG_GUDANG, $model->area_gudang);
                $model->quantity_awal = $currentStock;
                $model->quantity_akhir = $model->quantity_awal + $model->quantity_masuk - $model->quantity_keluar;
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Data barang gudang berhasil disimpan.');
                    return $this->redirect(['view', 'id_gudang' => $model->id_gudang]);
                }
            }
        } else {
            $model->loadDefaultValues();
            $model->tanggal = date('Y-m-d');
            $model->kode = Gudang::KODE_BARANG_GUDANG;
            $model->quantity_keluar = 0; // Default untuk barang masuk gudang
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Pastikan kode tetap 1
            $model->kode = Gudang::KODE_BARANG_GUDANG;
            
            // Recalculate quantity_akhir
            $model->quantity_akhir = $model->quantity_awal + $model->quantity_masuk - $model->quantity_keluar;
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data berhasil diperbarui.');
                return $this->redirect(['view', 'id_gudang' => $model->id_gudang]);
            }
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
        Yii::$app->session->setFlash('success', 'Data berhasil dihapus.');

        return $this->redirect(['index']);
    }

    /**
     * Action untuk menampilkan form move area dengan parameter dari item tertentu
     * @param int $barang_id
     * @param int $area_asal
     * @return string|\yii\web\Response
     */
    public function actionMoveArea($barang_id = null, $area_asal = null)
    {
        $model = new MoveAreaForm();
        
        // Jika dipanggil dengan parameter, set nilai default
        if ($barang_id && $area_asal) {
            $model->barang_id = $barang_id;
            $model->area_asal = $area_asal;
            
            // Cek stock yang tersedia di area asal
            $currentStock = Gudang::getCurrentStockByArea($barang_id, $area_asal);
            if ($currentStock <= 0) {
                Yii::$app->session->setFlash('error', 'Stock tidak tersedia di area asal.');
                return $this->redirect(['index']);
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            
            try {
                // Validasi stock tersedia di area asal
                $availableStock = Gudang::getCurrentStockByArea($model->barang_id, $model->area_asal);
                
                if ($availableStock < $model->jumlah) {
                    throw new \Exception("Stock tidak mencukupi. Stock tersedia: {$availableStock}");
                }
                
                // 1. Kurangi stock di area asal
                $stockAsal = Gudang::getCurrentStock($model->barang_id, Gudang::KODE_BARANG_GUDANG, $model->area_asal);
                
                $gudangAsal = new Gudang();
                $gudangAsal->tanggal = date('Y-m-d');
                $gudangAsal->barang_id = $model->barang_id;
                $gudangAsal->user_id = Yii::$app->user->id;
                $gudangAsal->kode = Gudang::KODE_BARANG_GUDANG;
                $gudangAsal->area_gudang = $model->area_asal;
                $gudangAsal->quantity_awal = $stockAsal;
                $gudangAsal->quantity_masuk = 0;
                $gudangAsal->quantity_keluar = $model->jumlah;
                $gudangAsal->quantity_akhir = $stockAsal - $model->jumlah;
                $gudangAsal->catatan = "Pindah ke Area {$model->area_tujuan} - {$model->catatan}";
                
                if (!$gudangAsal->save()) {
                    throw new \Exception('Gagal mengurangi stock di area asal: ' . json_encode($gudangAsal->errors));
                }
                
                // 2. Tambah stock di area tujuan
                $stockTujuan = Gudang::getCurrentStock($model->barang_id, Gudang::KODE_BARANG_GUDANG, $model->area_tujuan);
                
                $gudangTujuan = new Gudang();
                $gudangTujuan->tanggal = date('Y-m-d');
                $gudangTujuan->barang_id = $model->barang_id;
                $gudangTujuan->user_id = Yii::$app->user->id;
                $gudangTujuan->kode = Gudang::KODE_BARANG_GUDANG;
                $gudangTujuan->area_gudang = $model->area_tujuan;
                $gudangTujuan->quantity_awal = $stockTujuan;
                $gudangTujuan->quantity_masuk = $model->jumlah;
                $gudangTujuan->quantity_keluar = 0;
                $gudangTujuan->quantity_akhir = $stockTujuan + $model->jumlah;
                $gudangTujuan->catatan = "Pindah dari Area {$model->area_asal} - {$model->catatan}";
                
                if (!$gudangTujuan->save()) {
                    throw new \Exception('Gagal menambah stock di area tujuan: ' . json_encode($gudangTujuan->errors));
                }
                
                $transaction->commit();
                Yii::$app->session->setFlash('success', "Berhasil memindahkan {$model->jumlah} unit barang dari Area {$model->area_asal} ke Area {$model->area_tujuan}");
                return $this->redirect(['index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('move-area', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Gudang model based on its primary key value (with kode = 1).
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_gudang Id Gudang
     * @return Gudang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_gudang)
    {
        if (($model = Gudang::findOne(['id_gudang' => $id_gudang, 'kode' => Gudang::KODE_BARANG_GUDANG])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Get stock untuk AJAX request
     */
    public function actionGetStock()
    {
        $barang_id = Yii::$app->request->post('barang_id');
        $kode = Yii::$app->request->post('kode', Gudang::KODE_BARANG_GUDANG);
        $area_gudang = Yii::$app->request->post('area_gudang', 1);

        if ($barang_id) {
            $stock = Gudang::getStockTerbaru($barang_id, $kode, $area_gudang);
            
            if ($stock) {
                return $this->asJson(['quantity_akhir' => $stock->quantity_akhir]);
            }
        }

        return $this->asJson(['quantity_akhir' => 0]);
    }

    /**
     * Action khusus untuk menampilkan data penggunaan (kode 2)
     * Redirect ke StockController
     */
    public function actionPenggunaan()
    {
        return $this->redirect(['stock/index']);
    }
}