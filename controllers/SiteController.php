<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\laporanproduksi;
use app\models\PesanDetail;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'logout', 'panduan'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['admin'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->roleName === 'Admin';
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['super-admin'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->roleName === 'Super Admin';
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['operator'],
                        'matchCallback' => function ($rule, $action) {
                            return \Yii::$app->user->identity->roleName === 'Operator';
                        }
                    ],
                    [
                        'actions' => ['error'],
                        'allow' => true,
                        'roles' => ["?", "@"],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // kode lain untuk halaman index
        $pesanDetails = PesanDetail::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        $laporanProduksi = laporanproduksi::find()
            ->orderBy(['tanggal_kerja' => SORT_DESC])
            ->limit(5)
            ->all();
        return $this->render('index', [
            'pesanDetails' => $pesanDetails,
            'laporanProduksi' => $laporanProduksi
        ]);
    }

    public function actionPanduan()
    {
        // kode lain untuk halaman index
        return $this->render('panduan');
    }

    public function actionAdmin()
    {
        return $this->render('admin');
    }

    public function actionSuperAdmin()
    {
        return $this->render('super-admin');
    }

    public function actionOperator()
    {
        return $this->render('operator');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */


    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->user->setReturnUrl(['site/index']); // Set return URL ke site/index
            return $this->goBack(); // Redirect ke returnUrl atau ke site/index
        }

        $model->password = '';
        $this->layout = false;
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
