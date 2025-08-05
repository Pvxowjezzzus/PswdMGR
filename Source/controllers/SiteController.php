<?php

namespace app\controllers;

use app\models\Passwords;
use Yii;
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
                'class' => AccessControl::class,
                'only' => ['index', 'hash'], // Ограничиваем доступ к этим действиям
                'rules' => [
                    [
                        'allow' => false, // Запрещаем доступ неавторизованным пользователям
                        'roles' => ['?'], // "?" означает гостя (неавторизованный пользователь)
                    ],
                    [
                        'allow' => true, // Разрешаем доступ авторизованным пользователям
                        'roles' => ['@'], // "@" означает авторизованный пользователь
                    ],
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
            $user = Yii::$app->user->identity;

            if (Yii::$app->authManager->checkAccess($user->id, 'Администраторы')) {
                return $this->redirect(['/admin/passwords/index']);
            } else {
                return $this->redirect(['/site/index']);
            }
        }

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
     * Displays about page.
     *
     * @return string
     */
    public function actionHash($hash)
    {
        $auth = Yii::$app->authManager;
        $userId = Yii::$app->user->id;

        Yii::error('Ищем хеш: ' . $hash, 'application');

        // Поиск пароля в базе
        if (Yii::$app->user->can('Администраторы')) {
            $pass = Passwords::findOne(['hash' => $hash]);
        } else {
            $roles = $auth->getRolesByUser($userId);
            $roleNames = array_keys($roles);

            $pass = Passwords::find()
                ->joinWith('roles')
                ->where(['hash' => $hash])
                ->andWhere(['password_role.role_name' => $roleNames])
                ->one();
        }

        if (!$pass) {
            Yii::error('Хеш не найден или доступ запрещен: ' . $hash, 'application');
            Yii::$app->session->setFlash('error', 'Пароль не найден или у вас нет доступа.');
            return $this->redirect(['site/index']);
        }

        try {
            // Расшифровка пароля
            $decryptedPassword = Passwords::decryptPassword($pass->hash);
            if ($decryptedPassword === false) {
                throw new \Exception('Не удалось расшифровать пароль');
            }
        } catch (\Exception $e) {
            Yii::error('Ошибка расшифровки пароля: ' . $e->getMessage(), 'application');
            Yii::$app->session->setFlash('error', 'Ошибка расшифровки пароля.');
            return $this->redirect(['site/index']);
        }

        return $this->render('hash', [
            'pass' => $pass,
            'decryptedPassword' => $decryptedPassword,
        ]);
    }

}
