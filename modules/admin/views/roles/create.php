<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Добавить роль';
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="organizations-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <div class="organizations-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="form-group field-organizations-name required has-error">
                <label class="control-label">Наименование</label>
                <input type="text" name="name" class="form-control" required>
                <br>
                <label>Описание</label>
                <textarea type="text" name="description" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
