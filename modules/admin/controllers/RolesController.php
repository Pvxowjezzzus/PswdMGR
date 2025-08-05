<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\rbac\DbManager;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class RolesController extends Controller
{
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();

        return $this->render('index', [
            'roles' => $roles,
        ]);
    }

    public function actionAssign($userId)
    {
        $auth = Yii::$app->authManager;
        $user = User::findOne($userId);

        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $roles = $auth->getRoles();
        $assignedRoles = array_keys($auth->getRolesByUser($userId));

        if (Yii::$app->request->post()) {
            $selectedRoles = Yii::$app->request->post('roles', []);

            // Удаляем старые роли
            $auth->revokeAll($userId);

            // Назначаем новые роли
            foreach ($selectedRoles as $roleName) {
                $role = $auth->getRole($roleName);
                if ($role) {
                    $auth->assign($role, $userId);
                }
            }

            Yii::$app->session->setFlash('success', 'Роли обновлены.');

            // Редирект на страницу пользователя (view)
            return $this->redirect(['users/view', 'id' => $userId]);
        }

        return $this->render('assign', [
            'user' => $user,
            'roles' => $roles,
            'assignedRoles' => $assignedRoles,
        ]);
    }


    public function actionDelete($name)
    {
        if ($name === 'Администраторы') {
            Yii::$app->session->setFlash('error', 'Эту роль нельзя удалить!');
            return $this->redirect(['index']);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if ($role) {
            $auth->remove($role);
            Yii::$app->session->setFlash('success', 'Роль удалена.');
        } else {
            Yii::$app->session->setFlash('error', 'Роль не найдена.');
        }

        return $this->redirect(['index']);
    }

    public function actionCreate()
    {
        $auth = Yii::$app->authManager;

        if (Yii::$app->request->post()) {
            $name = Yii::$app->request->post('name');
            $description = Yii::$app->request->post('description');
            if (!empty($name)) {
                $role = $auth->createRole($name);
                $role->description = $description; // Добавляем описание
                $auth->add($role);

                // Автоматически добавляем новую роль в "Администраторы"
                $adminRole = $auth->getRole('Администраторы');
                if ($adminRole && !$auth->hasChild($adminRole, $role)) {
                    $auth->addChild($adminRole, $role);
                }
                Yii::$app->session->setFlash('success', 'Роль добавлена.');
            }
            return $this->redirect(['index']);
        }

        return $this->render('create');
    }

    public function actionView($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if (!$role) {
            throw new NotFoundHttpException('Роль не найдена.');
        }

        // Получаем всех пользователей с этой ролью
        $users = Yii::$app->db->createCommand("
            SELECT user.id, user.username 
            FROM auth_assignment 
            JOIN user ON auth_assignment.user_id = user.id 
            WHERE auth_assignment.item_name = :role
        ")->bindValue(':role', $role->name)->queryAll();

        return $this->render('view', [
            'role' => $role,
            'users' => $users
        ]);
    }

    public function actionUpdate($name)
    {
        if ($name === 'Администраторы') {
            Yii::$app->session->setFlash('error', 'Роль "Администраторы" нельзя редактировать.');
            return $this->redirect(['index']);
        }

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if (!$role) {
            throw new NotFoundHttpException('Роль не найдена.');
        }

        if (Yii::$app->request->isPost) {
            $newName = Yii::$app->request->post('name');
            $newDescription = Yii::$app->request->post('description');

            if ($newName && $newName !== $role->name) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 1. Создаем новую роль в auth_item
                    $newRole = new yii\rbac\Role();
                    $newRole->name = $newName;
                    $newRole->description = $newDescription;
                    $auth->add($newRole); // Добавляем новую роль в RBAC

                    // 2. Обновляем связанные записи в password_role
                    Yii::$app->db->createCommand()->update('password_role', ['role_name' => $newName], ['role_name' => $name])->execute();

                    // 3. Удаляем старую роль
                    $auth->remove($role);

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Роль успешно обновлена.');
                    return $this->redirect(['view', 'name' => $newName]);
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Ошибка: ' . $e->getMessage());
                }
            } else {
                // Обновляем только описание, если имя не меняется
                $role->description = $newDescription;
                $auth->update($name, $role);
                Yii::$app->session->setFlash('success', 'Описание роли обновлено.');
                return $this->redirect(['view', 'name' => $name]);
            }
        }

        return $this->render('update', [
            'role' => $role,
        ]);
    }


}
