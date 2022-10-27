<?php

declare(strict_types=1);

/**
 * @var \app\models\Message[] $messages
 */
?>
<h5 style="text-align: center">Входящие сообщения</h5>
<hr>
<?php if ($messages !== []): ?>
<?php else: ?>
<p style="text-align: center;color: grey">У вас пока нет сообщений</p>
    <img src="<?= IMAGES ?>empty-box.webp" alt="no messages" style="max-width: 100%">
<?php endif ?>
