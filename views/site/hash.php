<?php

/** @var yii\web\View $this */
/** @var $pass array */

use yii\helpers\Url;

$this->title = 'Hash';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h4>У Вас есть 5 секунд чтобы скопировать пароль</h4>
    <h4><?= htmlspecialchars($decryptedPassword) ?></h4>
</div>
<script type="text/javascript">
    setTimeout(function() {
         window.location.href = "<?= Url::to(['site/index']); ?>"; // Переадресация на другую страницу
         window.history.back(); // Возврат на пустую вкладку (закрытие главной страницы после переадресации)
    }, 5000); // Задержка 5 секунд
</script>