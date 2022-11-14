<?php

declare(strict_types=1);

/**
 * @var string $username
 * @var string $feed
 */
?>
<h6>
    Пользователь
    <a href="<?= HOST ?>/users/<?= $username ?>"> <?= $username ?> </a>
    оставил обратную связь
</h6>
<p><?= $feed ?></p>
