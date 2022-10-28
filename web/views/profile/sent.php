<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 * @var int $pages
 * @var int $page
 */

use app\components\MessageWidget;

?>
<h5 style="text-align: center;display: block">Отправленные сообщения</h5>
<hr>
<?php if ($messages !== []): ?>
    <?php if ($pages > 1): ?>
        <span>
            <?= 1 + (($page - 1) * 20) ?>
            -
            <?php
            if ($page !== $pages) {
                echo 20 + (($page - 1) * 20);
            } else {
                echo count($messages) + (($page - 1) * 20);
            }
            ?>
        </span>
        <button class="btn-page" value="<?= $page - 1 ?>"
            <?php if ($page === 1): ?>
                disabled
                style="color: grey"
            <?php endif ?>
        >
            &larr;
        </button>
        <button class="btn-page" value="<?= $page + 1 ?>"
            <?php if ($page === $pages): ?>
                disabled
                style="color: grey"
            <?php endif ?>
        >
            &rarr;
        </button>
    <?php endif ?>
    <?php foreach ($messages as $message): ?>
        <div class="message">
            <?= MessageWidget::widget([
                'head' => $message->getRecipientUsername(),
                'subject' => $message->getSubject(),
                'timestamp' => $message->getTimestamp()
            ]) ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <p style="text-align: center;color: grey">
        У вас пока нет сообщений, но вы можете кому-нибудь
        <button class="btn-basic" onclick="createMessageModal()">
            написать
        </button>
    </p>
    <img src="<?= IMAGES ?>empty-box.webp" alt="no messages" style="max-width: 100%">
<?php endif ?>
