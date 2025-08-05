<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $user app\models\User */
/* @var $roles yii\rbac\Role[] */
/* @var $assignedRoles array */
$this->title = 'Назначение ролей пользователю: ' . $user->username;
?>

<h3><?= Html::encode($this->title) ?></h3>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <label>Выберите роли</label>
    <select name="roles[]" class="form-control" multiple>
        <?php foreach ($roles as $role): ?>
            <option value="<?= $role->name ?>" <?= in_array($role->name, $assignedRoles) ? 'selected' : '' ?>>
                <?= Html::encode($role->name) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
