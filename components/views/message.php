<?php

declare(strict_types=1);

/**
 * @var string $head
 * @var string $subject
 * @var string $timestamp
 * @var int $pages
 * @var int $id
 */

use src\helpers\NormalizeData;

?>
<a class="a-btn" href="/profile/message/<?= $id ?>" target="_blank">
<span>
    <?= $head ?>
</span>
    <span style="justify-content: space-between">
    <span>
        <?= $subject ?>
    </span>
    <span style="font-size: small; font-style: italic">
        <?= NormalizeData::passedTime($timestamp) ?>
    </span>
</span>
</a>