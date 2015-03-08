<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Post;
use \yii\base\HttpException;

class SiteController extends Controller
{
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

    public function actionIndex()
    {
        $post = new Post();
        $posts = $post->find()->all();

        return $this->render('index',array('posts' => $posts));
    }

    public function actionRead($id = NULL)
    {
        /*if ($id === NULL)
            throw new HttpException(404, 'Not Found');*/

        //$post = Post::find($id);

        $post = Post::find()->where(['id' => $id])->one();


        /*if ($post === NULL)
            throw new HttpException(404, 'Document Does Not Exist');*/

        return $this->render('read', array(
            'post' => $post
        ));

    }

    public function actionDelete($id = NULL)
    {
        if ($id === NULL)
        {
            Yii::$app->session->setFlash('PostDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $post = Post::find()->where(['id' => $id])->one();

        if ($post === NULL)
        {
            Yii::$app->session->setFlash('PostDeletedError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $post->delete();

        Yii::$app->session->setFlash('PostDeleted','El Post ha sido borrado');
        Yii::$app->getResponse()->redirect('../listarPosts');
    }

    public function actionCreate()
    {
        $model = new Post();
        if (isset($_POST['Post']))
        {
            $model->title = $_POST['Post']['title'];
            $model->body = $_POST['Post']['body'];

            if ($model->save()) {
                Yii::$app->session->setFlash('PostCreated','El post ha sido creado correctamente');
                //Yii::$app->response->redirect(array('site/read', 'id' => $model->id));
                Yii::$app->response->redirect('viewPost/' . $model->id);
            }
        } else {
            echo $this->render('create', array(
                'model' => $model
            ));
        }
    }

    public function actionUpdate($id = NULL)
    {
        if ($id === NULL)
        {
            Yii::$app->session->setFlash('PostUpdateError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        $model = Post::find()->where(['id' => $id])->one();

        if ($model === NULL)
        {
            Yii::$app->session->setFlash('PostUpdateError');
            Yii::$app->getResponse()->redirect(array('site/index'));
        }

        if (isset($_POST['Post']))
        {
            $model->title = $_POST['Post']['title'];
            $model->body = $_POST['Post']['body'];

            if ($model->save()) {
                Yii::$app->session->set('user.attribute','sebas');
                Yii::$app->session->setFlash('PostCreated','El post ha sido actualizado correctamente');
                //Yii::$app->response->redirect(array('site/read', 'id' => $model->id));
                Yii::$app->response->redirect('../viewPost/' . $model->id);
            }
        } else {
            echo $this->render('create', array(
                'model' => $model
            ));
        }
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
