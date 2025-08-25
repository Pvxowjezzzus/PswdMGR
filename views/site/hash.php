<?php

/** @var yii\web\View $this */
/** @var $pass array */

use yii\helpers\Url;

$this->title = 'Hash';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <hr class="hash__underline">
    <h4 class="hash__header">У Вас есть 5 секунд чтобы скопировать пароль</h4>
    <h4 class="password__inline" onclick="copyHash(this)"><?= htmlspecialchars($decryptedPassword) ?></h4>
    <div id="notification" class="notification">
        <div class="notification-content">
            <span class="notification-title"></span>
            <span class="notification-message"></span>
        </div>
    </div>
    <hr class="hash__underline">
</div>
<script type="text/javascript">
    function copyHash(elem) {
        const text = elem.innerText;
        const textarea = document.createElement("textarea");
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showNotification(status = 1);
            elem.style.color = "#1e7c26";
        } catch (err) {
            console.error('Ошибка при копировании', err);
            showNotification(status = 0);
            elem.style.color = "#991616ff";
        }
        document.body.removeChild(textarea);
    }

    setTimeout(function() {
        window.location.href = "<?= Url::to(['site/index']); ?>"; // Переадресация на другую страницу
        window.history.back(); // Возврат на пустую вкладку (закрытие главной страницы после переадресации)
    }, 5000); // Задержка 5 секунд

    function showNotification(status) { // Показ уведомления о копировании
        const notification = document.getElementById('notification');
        if (status == 1) {
            notification.querySelector('.notification-title').innerText = 'Успешно';
            notification.querySelector('.notification-message').innerText = 'Текст скопирован в буфер обмена!';
        } else if (status == 0) {
            notification.querySelector('.notification-title').innerText = 'Ошибка';
            notification.querySelector('.notification-message').innerText = 'Не удалось скопировать текст!';
        }
        notification.classList.add('show');


        setTimeout(function() {
            notification.classList.remove('show');
        }, 3000);
    }
</script>