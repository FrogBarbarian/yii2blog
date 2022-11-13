<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 * @var \yii\web\View $this
 */

use app\assets\MailboxAsset;

$this->title = 'Сообщения';
MailboxAsset::register($this);
?>
<div class="mailbox-main">
    <div class="mailbox-buttonbar">
        <button name="inboxMails" type="button" class="toolbar-button" title="Входящие">
            <img src="<?= IMAGES ?>mailbox/button-mail.svg" alt="mail">
        </button>
        <button name="sentMails" type="button" class="toolbar-button" title="Отправленные">
            <img src="<?= IMAGES ?>mailbox/button-sent.svg" alt="sent">
        </button>
        <button name="spamMails" type="button" class="toolbar-button" title="Спам">
            <img src="<?= IMAGES ?>mailbox/button-spam.svg" alt="spam">
        </button>
        <button name="newMessage" type="button" class="toolbar-button mt-3" title="Новое сообщение">
            <img src="<?= IMAGES ?>mailbox/button-new-message.svg" alt="new">
        </button>
        <button name="refreshMessages" type="button" class="toolbar-button" title="Обновить">
            <img src="<?= IMAGES ?>mailbox/button-update.svg" alt="refresh">
        </button>
    </div>
    <div class="mailbox-mails-container" id="mails"></div>
</div>
