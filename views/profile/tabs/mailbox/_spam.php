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
<h5 class="text-center">Спам</h5>
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
        <div class="mailbox-message">
            <?= MessageWidget::widget([
                'head' => $message->getRecipientUsername(),
                'subject' => $message->getSubject(),
                'timestamp' => $message->getTimestamp(),
                'id' => $message->getId(),
            ]) ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <p class="text-center text-secondary">
        Вы еще не отметили ни одно сообщение как спам.
    </p>
    <img class="w-100" src="<?= IMAGES ?>empty-box.webp" alt="no messages">
<?php endif ?>
