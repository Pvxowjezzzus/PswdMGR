<?php

use app\models\Passwords;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\PasswordsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пароли';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="passwords-index">
    <div class="container">
        <div class="row">
            <div class="col">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="col text-end">
                <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    </br>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_service',
                'value' => function($model) {
                    return $model->service->name;
                }
            ],
            [
                'attribute' => 'id_organization',
                'value' => function($model) {
                    return $model->organization->name;
                }
            ],
            [
                'attribute' => 'roles',
                'label' => 'Роли',
                'value' => function ($model) {
                    return $model->roles ?: 'Нет ролей';
                },
                'format' => 'raw',
            ],
            'comment',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Passwords $model, $key, $index, $column) {
                    if ($action === 'view') {
                        return Url::toRoute([$action, 'id' => $model->id]);
                    }
                    return '#';
                }
            ],
        ],
    ]); ?>

</div>
