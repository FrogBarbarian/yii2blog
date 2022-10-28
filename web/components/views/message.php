<?php

declare(strict_types=1);

/**
 * @var string $head
 * @var string $subject
 * @var string $timestamp
 */

use src\helpers\NormalizeData;

?>
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