<?php

use app\models\Organizations;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\OrganizationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Организации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organizations-index">

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

            //'id',
            'name',
            'description:ntext',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                'urlCreator' => function ($action, Organizations $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
