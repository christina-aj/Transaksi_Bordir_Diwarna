<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use yii;
use app\models\Stock;
use app\models\StockSearch;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StockController implements the CRUD actions for Stock model.
 */
class StockController extends Controller
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
     * Lists all Stock models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StockSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Stock model.
     * @param int $stock_id Stock ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($stock_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($stock_id),
        ]);
    }
    public function actionCreate()
    {
        $modelStocks = [new Stock()];

        if (Yii::$app->request->isPost) {
            $modelStocks = ModelHelper::createMultiple(Stock::classname());

            // Load data dari POST ke model
            if (Model::loadMultiple($modelStocks, Yii::$app->request->post())) {

                // Set skenario create
                foreach ($modelStocks as $modelStock) {
                    $modelStock->scenario = Stock::SCENARIO_CREATE;
                }

                // Log data setelah load
                foreach ($modelStocks as $index => $modelStock) {
                    Yii::info("Model Stock #$index after load: " . json_encode($modelStock->attributes), 'debug');
                }

                // Validasi model setelah skenario diterapkan
                if (Model::validateMultiple($modelStocks)) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        foreach ($modelStocks as $index => $modelStock) {
                            // Isi field otomatis sebelum menyimpan
                            $modelStock->tambah_stock = date('Y-m-d');
                            $modelStock->quantity_awal = $this->getCurrentStock($modelStock->barang_id);
                            $modelStock->quantity_masuk = 0;
                            $modelStock->quantity_akhir = $modelStock->quantity_awal + $modelStock->quantity_masuk - $modelStock->quantity_keluar;
                            $modelStock->created_at = date('Y-m-d');
                            $modelStock->updated_at = date('Y-m-d');

                            if (!$modelStock->save()) {
                                Yii::$app->session->setFlash('error', "Failed to save item #{$index}: " . json_encode($modelStock->getErrors()));
                                throw new \yii\db\Exception('Failed to save items.');
                            }
                        }

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', 'All items saved successfully.');
                        return $this->redirect(['index']);
                    } catch (\yii\db\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Database error: ' . $e->getMessage());
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                    }
                } else {
                    // Flash message untuk error validasi
                    $allErrors = [];
                    foreach ($modelStocks as $index => $modelStock) {
                        $errors = $modelStock->getErrors();
                        if (!empty($errors)) {
                            $allErrors[] = "Item #{$index} errors: " . json_encode($errors);
                        }
                    }
                    Yii::$app->session->setFlash('error', 'Validation failed: ' . implode(' | ', $allErrors));
                }
            } else {
                Yii::$app->session->setFlash('error', "Data failed to load into modelStocks.");
            }
        }

        return $this->render('create', [
            'modelStocks' => $modelStocks,
            'isReadonly' => true,
        ]);
    }


    /**
     * Creates a new Stock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    /**
     * Updates an existing Stock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $stock_id Stock ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Deletes an existing Stock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $stock_id Stock ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Finds the Stock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $stock_id Stock ID
     * @return Stock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($stock_id)
    {
        if (($model = Stock::findOne(['stock_id' => $stock_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function getCurrentStock($barang_id)
    {
        $currentStock = Stock::find()->where(['barang_id' => $barang_id])->orderBy(['created_at' => SORT_DESC])->one();
        return $currentStock ? $currentStock->quantity_akhir : 0; // Jika tidak ada stok sebelumnya, mulai dari 0
    }

    public function actionSearch($query)
    {
        $data = Barang::find()
            ->select(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang', 'stock.quantity_akhir'])
            ->innerJoin('stock', 'stock.barang_id = barang.barang_id') // Join inner untuk memastikan hanya stok yang ada
            ->where(['like', 'barang.nama_barang', $query])
            ->orWhere(['like', 'barang.kode_barang', $query])
            ->andWhere(['>', 'stock.quantity_akhir', 0]) // Hanya ambil barang yang memiliki stok lebih dari 0
            ->asArray()
            ->all();

        Yii::info("Data Search Result: " . json_encode($data), 'debug');
        return \yii\helpers\Json::encode($data); // Kembalikan dalam format JSON
    }

    public function actionGetStock($barang_id)
    {
        $stock = Stock::find()
            ->joinWith('barang') // Asumsi ada relasi ke tabel barang jika perlu
            ->where(['barang.barang_id' => $barang_id])
            ->orderBy(['stock.stock_id' => SORT_DESC])
            ->one();

        return \yii\helpers\Json::encode(['quantity_akhir' => $stock ? $stock->quantity_akhir : 0]);
    }
}
