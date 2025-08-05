<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var yii\rbac\Role $role */
/** @var array $users */

$this->title = $role->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="role-view">

    <div class="container">
        <div class="row">
            <div class="col">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="col text-end">
                <?php if ($role->name !== 'Администраторы'): ?>
                    <?= Html::a('Редактировать', ['update', 'name' => $role->name], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Удалить', ['delete', 'name' => $role->name], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить эту роль?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </br>

    <?= DetailView::widget([
        'model' => $role,
        'attributes' => [
            [
                 'label' => 'Наименование',
                'attribute' => 'name',
            ],

            [
                'label' => 'Описание',
                'value' => $role->description ?: '(не указано)',
            ],
            [
                'label' => 'Пользователи с этой ролью',
                'format' => 'raw',
                'value' => function () use ($users) {
                    if (empty($users)) {
                        return '<span class="text-muted">(Нет пользователей)</span>';
                    }
                    $list = '<ul>';
                    foreach ($users as $user) {
                        $list .= '<li>' . Html::encode($user['username']) . ' (ID: ' . $user['id'] . ')</li>';
                    }
                    $list .= '</ul>';
                    return $list;
                },
            ],
        ],
    ]) ?>
</div>
