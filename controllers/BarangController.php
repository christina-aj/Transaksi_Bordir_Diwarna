<?php

namespace app\controllers;

use app\helpers\ModelHelper;
use app\models\Barang;
use app\models\BarangSearch;
use app\models\Gudang;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\base\Model;

/**
 * BarangController implements the CRUD actions for Barang model.
 */
class BarangController extends BaseController
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
     * Lists all Barang models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BarangSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Barang model.
     * @param int $barang_id Barang ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($barang_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($barang_id),
        ]);
    }

    /**
     * Creates a new Barang model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $modelBarangs = [new Barang()];

        if (Yii::$app->request->isPost) {
            // Load multiple instances
            $modelBarangs = ModelHelper::createMultiple(Barang::classname());
            if (Model::loadMultiple($modelBarangs, Yii::$app->request->post())) {
                foreach ($modelBarangs as $index => $modelBarang) {
                    Yii::info("Loaded Model Barang #$index: " . json_encode($modelBarang->attributes), 'modelData');
                }
            } else {
                Yii::info("Data failed to load into modelBarangs.", 'loadError');
            }

            // Validate models
            if (Model::validateMultiple($modelBarangs)) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    foreach ($modelBarangs as $index => $modelBarang) {
                        $modelBarang->warna = $modelBarang->warna ?? null;
                        $modelBarang->created_at = date('Y-m-d H:i:s');
                        $modelBarang->updated_at = date('Y-m-d H:i:s');

                        if (!$modelBarang->save()) {
                            Yii::$app->session->setFlash('error', "Failed to save item #{$index}: " . json_encode($modelBarang->getErrors()));
                            throw new \yii\db\Exception('Failed to save items.');
                        }
                    }

                    $transaction->commit();

                    // Set success flash message
                    Yii::$app->session->setFlash('success', 'Data berhasil disimpan.');

                    Yii::$app->session->set('modelBarangs', $modelBarangs);
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
                foreach ($modelBarangs as $index => $modelBarang) {
                    $errors = $modelBarang->getErrors();
                    if (!empty($errors)) {
                        $allErrors[] = "Item #{$index} errors: " . json_encode($errors);
                    }
                }
                Yii::$app->session->setFlash('error', 'Validation failed: ' . implode(' | ', $allErrors));
            }
        }

        return $this->render('create', [
            'modelBarangs' => $modelBarangs,
            'isReadonly' => true,
        ]);
    }


    /**
     * Updates an existing Barang model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $barang_id Barang ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($barang_id)
    {
        // Cari model Barang berdasarkan ID
        $modelBarang = Barang::findOne($barang_id);

        if (!$modelBarang) {
            throw new NotFoundHttpException("Barang tidak ditemukan.");
        }

        // Cek jika ada data yang di-post
        if ($modelBarang->load(Yii::$app->request->post()) && $modelBarang->validate()) {
            $modelBarang->updated_at = date('Y-m-d H:i:s'); // Update timestamp

            // Simpan perubahan
            if ($modelBarang->save()) {
                Yii::$app->session->setFlash('success', 'Data barang berhasil diperbarui.');
                return $this->redirect(['index']); // Sesuaikan redirect sesuai kebutuhan
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan data barang.');
            }
        } else if ($modelBarang->hasErrors()) {
            Yii::$app->session->setFlash('error', 'Validasi gagal: ' . json_encode($modelBarang->getErrors()));
        }

        return $this->render('update', [
            'modelBarang' => $modelBarang,
        ]);
    }

    /**
     * Deletes an existing Barang model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $barang_id Barang ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($barang_id)
    {
        $this->findModel($barang_id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSearch($query)
    {
        $data = Barang::find()
            ->select(['barang.barang_id', 'barang.kode_barang', 'barang.nama_barang', 'gudang.quantity_akhir']) // Mengambil field stock
            ->leftJoin('gudang', 'gudang.barang_id = barang.barang_id') // Join dengan tabel stock
            ->where(['like', 'barang.nama_barang', $query])
            ->orwhere(['like', 'barang.kode_barang', $query])
            ->andWhere(['>', 'gudang.quantity_akhir', 0]) // Hanya ambil barang yang memiliki stock lebih dari 0
            ->asArray()
            ->all();

        return \yii\helpers\Json::encode($data); // Kembalikan dalam format JSON
    }





    /**
     * Finds the Barang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $barang_id Barang ID
     * @return Barang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($barang_id)
    {
        if (($model = Barang::findOne(['barang_id' => $barang_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
