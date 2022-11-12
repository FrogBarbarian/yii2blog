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
<a class="a-btn d-flex justify-content-between w-100" href="/message?id=<?= $id ?>" target="_blank">
    <span>
        <?= $head ?>
        <span class="bg-black" style="padding-left: 1px; margin: 0 5px"></span>
        <?= $subject ?>
    </span>
    <span class="small fst-italic">
        <?= NormalizeData::passedTime($timestamp) ?>
    </span>
</a>
