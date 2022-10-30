<?php

declare(strict_types=1);

/**
 * @var \app\models\Message $message
 * @var bool $userIsSender
 */


$this->title = "Сообщение #{$message->getId()}";
\app\assets\TestAsset::register($this);
$this->registerJsFile('@web/assets/js/message.js');

?>
<!--<script src="../../assets/js/message.js"></script>-->
<div class="mx-lg-5 p-3 bg-white border border-dark border-1">
    <h5 class="text-center">
        <?php
        $format = 'Письмо %s <a class="author-link" href="/users/%s" target="_blank">%s</a>';
        $username = $userIsSender ? $message->getRecipientUsername() : $message->getSenderUsername();
        $toOrFrom = $userIsSender ? 'пользователю' : 'от пользователя';
        echo sprintf($format, $toOrFrom, $username, $username);
        ?>
    </h5>
    <div class="p-2 mb-2 border border-dark border-1">
        <h6>
            <?= $message->getSubject() ?>
        </h6>
        <?= $message->getContent() ?>
    </div>
    <div>
        <?php if (!$userIsSender): ?>
            <button class="btn-basic">
                <!--TODO: рендер окна написания сообщения. Заполняем автора. Заполняем тему. Заполняет содержание, фокус на содержание-->
                Ответить
            </button>
            <button class="btn-basic">
                <!--TODO: Создаем жалобу. Отправляем сообщение в спам. Перенаправляем страницу-->
                Спам
            </button>
        <?php endif ?>
        <button class="btn-basic">
            <!--TODO: рендер окна написания сообщения. Заполняем тему. Заполняет содержание, фокус на автора-->
            Переслать
        </button>
        <button onclick="deleteMessage()" class="btn-basic">
            <!--TODO: Удаляем сообщение, перенаправляем страницу-->
            Удалить
        </button>
    </div>
</div>
