<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Passwords $model */

$this->title = $model->service->name;
$this->params['breadcrumbs'][] = ['label' => 'Пароли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="passwords-view">

    <div class="container">
        <div class="row">
            <div class="col">
                <h3><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="col text-end">
                <?= Html::button('Копировать ссылку', [
                    'class' => 'btn btn-success',
                    'id' => 'copy-button',
                    'data-link' => Yii::$app->request->hostInfo . "/hash/" . $model->hash
                ]) ?>
                <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    </br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id_organization',
                'value' => function($model) {
                    return $model->organization->name;
                }
            ],
            'hash',
            [
                'label' => 'Роли', // Название атрибута
                'value' => function ($model) {
                    // Получаем роли из таблицы password_role
                    $roles = Yii::$app->db->createCommand("
                    SELECT role_name FROM password_role WHERE password_id = :password_id
                ")->bindValue(':password_id', $model->id)->queryColumn();

                    return !empty($roles) ? implode(', ', $roles) : 'Нет ролей';
                },
                'format' => 'raw', // Оставляем текст без обработки
            ],
            'comment',
        ],
    ]) ?>

</div>
<?php
$script = <<<JS
document.addEventListener('DOMContentLoaded', function () {
    let copyButton = document.getElementById('copy-button');

    copyButton.addEventListener('click', function() {
        let link = this.getAttribute('data-link');

        if (navigator.clipboard && navigator.clipboard.writeText) {
            // Современный метод
            navigator.clipboard.writeText(link)
                .then(() => alert('Ссылка скопирована: ' + link))
                .catch(err => {
                    console.error('Ошибка Clipboard API:', err);
                    fallbackCopy(link);
                });
        } else {
            // Fallback для старых браузеров
            fallbackCopy(link);
        }
    });

    function fallbackCopy(text) {
        let textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            let successful = document.execCommand('copy');
            alert(successful ? 'Ссылка скопирована: ' + text : 'Ошибка копирования');
        } catch (err) {
            console.error('Ошибка execCommand:', err);
            alert('Ошибка копирования, попробуйте вручную.');
        }

        document.body.removeChild(textarea);
    }
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);

?>

