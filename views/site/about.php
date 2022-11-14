<?php

declare(strict_types=1);

/**
 * @var \yii\web\View $this
 */

$this->title = 'О сайте';
$this->registerJsFile('@js/mini/about.js');
?>
<div class="window-basic m-auto" style="max-width: 500px">
    <h6>О сайте</h6>
    <p>Наш сайт бла бла бла, новости, комментарии и всякое такое. Добро пожаловать, не нарушайте правила.</p>
    <p>Если у вас есть пожелания или замечания к работе, то вы можете отправить их через форму ниже.</p>
    <div class="div-input-basic" contenteditable="true"></div>
    <button class="btn-basic">Отправить</button>
</div>
