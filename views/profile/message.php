<?php

declare(strict_types=1);

/**
 * @var \app\models\Message $message
 * @var bool $userIsSender
 * @var \yii\web\View $this
 */

$this->title = "Сообщение #{$message->getId()}";
$this->registerJsFile('@web/assets/js/message.js');
$this->registerJsFile('@web/assets/js/message-modal.js');

?>
<div
        id="messageData"
        hidden
        data-id="<?= $message->getId() ?>"
        data-userIsSender ="<?= $userIsSender ?>"
        data-sender="<?= $message->getSenderUsername() ?>"
        data-recipient="<?= $message->getRecipientUsername() ?>"
></div>
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
        <h6 id="messageSubject">
            <?= $message->getSubject() ?>
        </h6>
        <div id="messageContent">
            <?= $message->getContent() ?>
        </div>
    </div>
    <div>
        <?php if (!$userIsSender): ?>
            <button id="replyButton" class="btn-basic">
                <!--TODO: рендер окна написания сообщения. Заполняем автора. Заполняем тему. Заполняет содержание, фокус на содержание-->
                Ответить
            </button>
            <button id="spamButton" class="btn-basic">
                <!--TODO: Создаем жалобу. Отправляем сообщение в спам. Перенаправляем страницу-->
                Спам
            </button>
        <?php endif ?>
        <button id="forwardButton" class="btn-basic">
            <!--TODO: рендер окна написания сообщения. Заполняем тему. Заполняет содержание, фокус на автора-->
            Переслать
        </button>
        <button id="deleteButton" class="btn-basic">
            <!--TODO: Удаляем сообщение, перенаправляем страницу-->
            Удалить
        </button>
    </div>
</div>
