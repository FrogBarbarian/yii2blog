<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 * @var int $pages
 * @var int $page
 */

use app\components\MessageWidget;
use app\components\PageSwitcherWidget;

?>
<h5 class="text-center">Входящие сообщения</h5>
<hr>
<?php if ($messages !== []): ?>
    <?php

    if ($pages > 1) {
        echo PageSwitcherWidget::widget([
            'page' => $page,
            'pages' => $pages,
            'objectsOnPage' => count($messages),
        ]);
    }

    ?>
    <?php foreach ($messages as $message): ?>
        <div class="mailbox-message" <?php if (!$message->getIsRead()): ?>style="font-weight: bold" <?php endif ?>>
            <?= MessageWidget::widget([
                'head' => $message->getSenderUsername(),
                'subject' => $message->getSubject(),
                'timestamp' => $message->getTimestamp(),
                'id' => $message->getId(),
            ]) ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <p class="text-center text-secondary">У вас пока нет сообщений</p>
    <img class="w-100" src="<?= IMAGES ?>empty-box.webp">
<?php endif ?>
