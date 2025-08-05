<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ServicesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Управление ролями';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ArrayDataProvider([
    'allModels' => $roles,
    'pagination' => [
        'pageSize' => 10, // Количество элементов на странице
    ],
]);
?>
<div class="services-index">
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

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
        'attribute' => 'name',
        'label' => 'Название роли',
        ],
        [
        'attribute' => 'description',
        'label' => 'Описание',
        ],
    
        [
            'template' => '{view}',
            'class' => 'yii\grid\ActionColumn',
            'urlCreator' => function ($action, $model, $key, $index) {
                return \yii\helpers\Url::to([$action, 'name' => $model->name]);
            },
        ],
        ],
    ]);
    ?>

</div>
