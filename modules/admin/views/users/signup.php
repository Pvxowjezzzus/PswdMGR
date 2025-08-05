<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Регистрация';
?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'signup-form',
        'options' => ['class' => 'form-horizontal'],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label("Пользователь") ?>

    <?= $form->field($model, 'email')->label("E-mail") ?>

    <?= $form->field($model, 'password')->passwordInput()->label("Пароль") ?>

    <?= $form->field($model, 'confirm_password')->passwordInput()->label("Повторите пароль") ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
