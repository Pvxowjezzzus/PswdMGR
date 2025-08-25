<?php

use app\models\Organizations;
use app\models\Services;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
/** @var yii\web\View $this */
/** @var app\models\Passwords $model */
/* @var $roles array */
 $this->registerJsFile(
        '@web/js/genPassword.js',
);
$this->title = 'Добавить пароль';
$this->params['breadcrumbs'][] = ['label' => 'Пароли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="passwords-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_organization')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Organizations::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите организацию ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>

  <?= $form->field($model, 'id_service')->widget(Select2::className(), [
        'data' => ArrayHelper::map(Services::find()->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите сервис ...'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ]); ?>
    <div class="password__block">
          <?= $form->field($model, 'plain_password')->textInput()->label("Пароль") ?>
        <div class="gen-pswd__block">
            <?= Html::button('Сгенерировать пароль', ['class' => 'btn btn-dark gen-pswd__btn', 'onclick' => 'genPswd()'], ) ?>
        </div>
    </div>

   

    <?= $form->field($model, 'comment')->textarea()->label("Комментарий") ?>

    <div class="form-group">
        <label>Выберите роли:</label>
        <?= Html::checkboxList('roles', [], $roles) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
