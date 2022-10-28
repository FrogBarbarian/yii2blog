<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 */

use app\components\MessageWidget;

?>
<h5 style="text-align: center;display: block">Отправленные сообщения</h5>
<hr>
<?php if ($messages !== []): ?>
    <?php foreach ($messages as $message): ?>
        <div class="message">
            <?= MessageWidget::widget([
                'head' => $message->getRecipientUsername(),
                'subject' => $message->getSubject(),
                'timestamp' => $message->getTimestamp(),
            ]) ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <p style="text-align: center;color: grey">
        У вас пока нет сообщений, но вы можете кому-нибудь
        <button onclick="createMessageModal()">
            написать
        </button>
    </p>
    <img src="<?= IMAGES ?>empty-box.webp" alt="no messages" style="max-width: 100%">
<?php endif ?>
