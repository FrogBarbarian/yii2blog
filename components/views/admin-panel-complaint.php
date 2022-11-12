<?php

declare(strict_types=1);

/**
 * @var \app\models\Complaint $complaint
 * @var string $link
 * @var string $targetObject
 */

use src\helpers\NormalizeData;
?>
<div id="complaint_<?= $complaint->getId() ?>">
    <p class="smaller">
        <a class="author-link" href="/users/<?= $complaint->getSenderUsername() ?>" target="_blank">
            <?= $complaint->getSenderUsername() ?>
        </a>
        <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
        отправил жалобу на
        <a class="author-link" href="<?= $link ?>" target="_blank">
            <?= $targetObject ?>
        </a>
    </p>
    <span class="x-small fst-italic d-block text-muted">
        Содержание жалобы:
    </span>
    <?= $complaint->getComplaint() ?>
    <button onclick="closeComplaint('<?= $complaint->getId() ?>')" class="btn-basic d-block">Закрыть жалобу</button>
    <hr class="mt-2 mb-3">
</div>
