<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use yii;
use app\models\Gudang;
use app\models\GudangSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockController implements the CRUD actions for Stock (using Gudang model with kode = 2).
 */
class StockController extends BaseController
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
     * Lists all Stock models (kode = 2 for penggunaan).
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new GudangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams, Gudang::KODE_PENGGUNAAN);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
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
     * Creates new Stock models (multiple items with kode = 2).
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $modelStocks = [new Gudang()];

        if (Yii::$app->request->isPost) {
            $modelStocks = ModelHelper::createMultiple(Gudang::classname());
            Model::loadMultiple($modelStocks, Yii::$app->request->post());

            // Isi semua field yang required sebelum validasi
            foreach ($modelStocks as $modelStock) {
                $modelStock->kode = Gudang::KODE_PENGGUNAAN;
                $modelStock->area_gudang = 1;
                $modelStock->quantity_masuk = 0;
                $modelStock->quantity_awal = $modelStock->quantity_awal; // dari form
                $modelStock->quantity_akhir = $modelStock->quantity_awal - $modelStock->quantity_keluar;
                $modelStock->tanggal = date('Y-m-d');
                $modelStock->user_id = $modelStock->user_id ?? Yii::$app->user->id;

                // Set catatan default
                if (empty($modelStock->catatan)) {
                    $modelStock->catatan = 'Penggunaan stock';
                }

                // Validasi stok cukup
                if ($modelStock->quantity_akhir < 0) {
                    Yii::$app->session->setFlash('error', "Stock tidak mencukupi untuk barang ID: {$modelStock->barang_id}");
                    return $this->redirect(['create']);
                }
            }

            if (Model::validateMultiple($modelStocks)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelStocks as $modelStock) {
                        if (!$modelStock->save(false)) {
                            throw new \Exception('Gagal menyimpan item stock');
                        }
                    }
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Semua item stock berhasil disimpan.');
                    return $this->redirect(['index']);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            } else {
                // Debug validation error
                foreach ($modelStocks as $index => $modelStock) {
                    Yii::info("Item #$index errors: " . json_encode($modelStock->getErrors()), 'debug');
                }
            }
        } else {
            // Set default untuk form baru
            foreach ($modelStocks as $model) {
                $model->loadDefaultValues();
                $model->tanggal = date('Y-m-d');
                $model->kode = Gudang::KODE_PENGGUNAAN;
                $model->quantity_masuk = 0;
            }
        }

        return $this->render('create', [
            'modelStocks' => $modelStocks,
            'isReadonly' => true,
        ]);
    }


    /**
     * Updates an existing Stock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id_gudang Id Gudang
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_gudang)
    {
        $model = $this->findModel($id_gudang);

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Pastikan kode tetap 2 untuk penggunaan
            $model->kode = Gudang::KODE_PENGGUNAAN;
            
            // Recalculate quantity_akhir
            $model->quantity_akhir = $model->quantity_awal + $model->quantity_masuk - $model->quantity_keluar;
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Data penggunaan stock berhasil diperbarui.');
                return $this->redirect(['view', 'id_gudang' => $model->id_gudang]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Stock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id_gudang Id Gudang
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_gudang)
    {
        $this->findModel($id_gudang)->delete();
        Yii::$app->session->setFlash('success', 'Data penggunaan stock berhasil dihapus.');

        return $this->redirect(['index']);
    }

    /**
     * Finds the Gudang model based on its primary key value (with kode = 2).
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id_gudang Id Gudang
     * @return Gudang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_gudang)
    {
        if (($model = Gudang::findOne(['id_gudang' => $id_gudang, 'kode' => Gudang::KODE_PENGGUNAAN])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Get current stock dari gudang (kode = 1) untuk barang tertentu
     */
    protected function getCurrentStock($barang_id)
    {
        return Gudang::getCurrentStock($barang_id, Gudang::KODE_PENGGUNAAN);
    }

    /**
     * Search barang untuk AJAX
     */
    public function actionSearch($query)
    {
        $data = Barang::find()
            ->select(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang'])
            ->leftJoin('gudang', 'gudang.barang_id = barang.barang_id AND gudang.kode = ' . Gudang::KODE_BARANG_GUDANG)
            ->where(['like', 'barang.nama_barang', $query])
            ->orWhere(['like', 'barang.kode_barang', $query])
            ->groupBy(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang'])
            ->asArray()
            ->all();

        // Tambahkan informasi stock dari penggunaan/stock (kode = 2) untuk setiap barang
        foreach ($data as &$item) {
            $item['quantity_akhir'] = Gudang::getCurrentStock($item['barang_id'], Gudang::KODE_PENGGUNAAN, 1);
        }


        Yii::info("Data Search Result: " . json_encode($data), 'debug');
        return \yii\helpers\Json::encode($data);
    }

    /**
     * Get stock untuk barang tertentu (AJAX)
     */
    public function actionGetStock($barang_id)
    {
        $quantity_akhir = Gudang::getCurrentStock($barang_id, Gudang::KODE_PENGGUNAAN, 1);
        return \yii\helpers\Json::encode(['quantity_akhir' => $quantity_akhir]);
    }
}