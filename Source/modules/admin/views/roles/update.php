<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\rbac\Role $role */

$this->title = 'Редактировать роль: ' . $role->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $role->name, 'url' => ['view', 'id' => $role->name]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="role-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="role-form">
        <?php $form = ActiveForm::begin(); ?>

        <label>Название роли</label>
        <?= Html::textInput('name', $role->name, ['class' => 'form-control']) ?>

        <label class="mt-3">Описание роли</label>
        <?= Html::textarea('description', $role->description, ['class' => 'form-control', 'rows' => 3]) ?>

        <div class="form-group mt-3">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
