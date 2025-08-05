<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Services $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="services-view">

    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Пользователь: <?= Html::encode($this->title) ?></h3>
            </div>
            <div class="col text-end">
                <?= Html::a('Назначить роли', ['/admin/roles/assign', 'userId' => $model->id], ['class' => 'btn btn-warning']) ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены что хотите удалить данного пользователя?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    </br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'username',
            'email:ntext',
            [
                'label' => 'Роли',
                'value' => function ($model) {
                    $roles = Yii::$app->authManager->getRolesByUser($model->id);
                    return implode(', ', array_keys($roles));
                },
            ],
        ],
    ]) ?>

</div>