<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Organizations $model */

$this->title = 'Редактировать организацию: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Организация', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="organizations-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
