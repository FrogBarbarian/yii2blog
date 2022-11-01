<?php

declare(strict_types=1);

/**
 * @var int $page
 * @var int $pages
 * @var int $objectsOnPage
 */

$factor = (($page - 1) * 20);
?>
<span>
    <?= 1 + $factor ?>
    -
    <?= ($page !== $pages ? 20 : $objectsOnPage) + $factor ?>
</span>
<button class="mailbox-page-switcher" value="<?= $page - 1 ?>"
    <?php if ($page === 1): ?>
        disabled
        style="color: grey"
    <?php endif ?>
>
    &larr;
</button>
<button class="mailbox-page-switcher" value="<?= $page + 1 ?>"
    <?php if ($page === $pages): ?>
        disabled
        style="color: grey"
    <?php endif ?>
>
    &rarr;
</button>
