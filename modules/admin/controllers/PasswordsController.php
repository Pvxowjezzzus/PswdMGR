<?php

namespace app\modules\admin\controllers;

use app\models\Passwords;
use app\models\PasswordsSearch;
use Yii;
use yii\base\Security;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PasswordsController implements the CRUD actions for Passwords model.
 */
class PasswordsController extends Controller
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
     * Lists all Passwords models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PasswordsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Passwords model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Passwords model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Passwords();
        $auth = Yii::$app->authManager;

        // Получаем список ролей, за исключением "Администраторы"
        $roles = ArrayHelper::map($auth->getRoles(), 'name', 'name');
        unset($roles['Администраторы']);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $password = $model->plain_password;

            // Шифруем пароль с использованием модели
            $encryptedPassword = Passwords::encryptPassword($password);

            // Сохраняем зашифрованный пароль
            $model->hash = $encryptedPassword;

            if ($model->save()) {
                // Сохраняем выбранные роли
                $selectedRoles = Yii::$app->request->post('roles', []);
                foreach ($selectedRoles as $roleName) {
                    Yii::$app->db->createCommand()->insert('password_role', [
                        'password_id' => $model->id,
                        'role_name' => $roleName,
                    ])->execute();
                }

                // Перенаправляем на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Updates an existing Passwords model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $auth = Yii::$app->authManager;

        // Получаем список ролей, исключая "Администраторы"
        $roles = ArrayHelper::map($auth->getRoles(), 'name', 'name');
        unset($roles['Администраторы']);

        // Получаем текущие роли, связанные с паролем
        $assignedRoles = Yii::$app->db->createCommand("
        SELECT role_name FROM password_role WHERE password_id = :password_id
    ")->bindValue(':password_id', $model->id)->queryColumn();

        if ($this->request->isPost && $model->load($this->request->post())) {
            // Если введен новый пароль, шифруем и сохраняем его
            if (!empty($model->plain_password)) {
                $model->hash = Passwords::encryptPassword($model->plain_password);
            }

            if ($model->save()) {
                // Удаляем старые роли
                Yii::$app->db->createCommand()
                    ->delete('password_role', ['password_id' => $model->id])
                    ->execute();

                // Добавляем новые выбранные роли
                $selectedRoles = Yii::$app->request->post('roles', []);
                foreach ($selectedRoles as $roleName) {
                    Yii::$app->db->createCommand()->insert('password_role', [
                        'password_id' => $model->id,
                        'role_name' => $roleName,
                    ])->execute();
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
            'assignedRoles' => $assignedRoles,
        ]);
    }


    /**
     * Deletes an existing Passwords model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    /**
     * Finds the Passwords model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Passwords the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Passwords::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
