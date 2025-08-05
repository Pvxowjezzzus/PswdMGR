<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование пользователя';
?>
<div class="services-update">
<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username')->textInput() ?>
<?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>

<h3>Изменение пароля (необязательно)</h3>
<?= $form->field($model, 'newPassword')->passwordInput()->label("Новый пароль") ?>
<?= $form->field($model, 'newPasswordRepeat')->passwordInput()->label("Повторите новый пароль") ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>
</div>

<?php ActiveForm::end(); ?>

