<?php

namespace app\controllers;

use app\models\Article;
use app\models\Category;
use app\models\CommentsForm;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
        $data = Article::getAll(1);
        $viewD['articles'] = $data['articles'];
        $viewD['pagination'] = $data['pagination'];

        $viewD['popular'] = Article::getPopular();
        $viewD['recent'] = Article::getRecent();
        $viewD['categories'] = Category::getAll();

        return $this->render('index', $viewD);
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


    public function actionView($id)
    {
        $viewD['article'] = Article::findOne($id);
        $viewD['popular'] = Article::getPopular();
        $viewD['recent'] = Article::getRecent();
        $viewD['categories'] = Category::getAll();
        $viewD['comments'] = $viewD['article']->getArticleComments();
        $viewD['commentsForm'] = new CommentsForm();
        $viewD['article']->viewedCounter();

        return $this->render('single', $viewD);
    }


    public function actionCategory($id)
    {
        $data = Category::getArticlesByCategory($id);
        $viewD['articles'] = $data['articles'];
        $viewD['pagination'] = $data['pagination'];

        $viewD['popular'] = Article::getPopular();
        $viewD['recent'] = Article::getRecent();
        $viewD['categories'] = Category::getAll();

        return $this->render('category', $viewD);
    }


    public function actionComment($id)
    {
        $model = new CommentsForm();

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->saveComment($id))
            {
                Yii::$app->session->setFlash('comment', 'Your comment will be added soon!');
                return $this->redirect(['site/view', 'id' => $id]);
            }
        }
    }
}
