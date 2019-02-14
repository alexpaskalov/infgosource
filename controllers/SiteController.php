<?php

namespace app\controllers;

use app\models\ModalForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\widgets\ActiveForm;
use GPH;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
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
        return $this->render('index');
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
            return $this->goBack();
        }

        $model->password = '';
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

    public function actionTree()
    {
        $this->view->title =  'Tree Yii2 Application for Infosource';


        $tree = [];
        $nodes1 = range(1,100,1);
        $max = 10;
        foreach ($nodes1 as $node) {
            $tree[$node] = range(1, rand(1,$max), 1);
        }

        $model = new ModalForm();

        return $this->render('tree', ['model' => $model,
            'tree' => $tree
        ]);


    }

    public function actionModal()
    {
        $form_model = new ModalForm();
        if(\Yii::$app->request->isAjax){
            $api_instance = new GPH\Api\DefaultApi();
            $api_key = "dc6zaTOxFJmzC"; // string | Giphy API Key.

            try {
                $result = $api_instance->gifsRandomGet($api_key);
            } catch (Exception $e) {
                echo 'Exception when calling DefaultApi->gifsRandomGet: ', $e->getMessage(), PHP_EOL;
            }

            return $result->getData()->getImageUrl();
        }
        if($form_model->load(\Yii::$app->request->post())){
            var_dump($form_model);
        }
        return $this->render('page', compact('form_model'));
    }
}
