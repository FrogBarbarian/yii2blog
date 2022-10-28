<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 * @var int $pages
 */

use app\components\MessageWidget;

?>
<h5 style="text-align: center">Входящие сообщения</h5>
<hr>
<?php if ($messages !== []): ?>
    <?php foreach ($messages as $message): ?>
        <div class="message" <?php if (!$message->getIsRead()): ?>style="font-weight: bold" <?php endif ?>>
            <?= MessageWidget::widget([
                'head' => $message->getSenderUsername(),
                'subject' => $message->getSubject(),
                'timestamp' => $message->getTimestamp(),
            ]) ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <p style="text-align: center;color: grey">У вас пока нет сообщений</p>
    <img src="<?= IMAGES ?>empty-box.webp" alt="no messages" style="max-width: 100%">
<?php endif ?>
