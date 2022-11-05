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
    <p style="font-size: smaller">
        <a class="author-link" href="/users/<?= $complaint->getSenderUsername() ?>" target="_blank">
            <?= $complaint->getSenderUsername() ?>
        </a>
        <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
        отправил жалобу на
        <a class="author-link" href="<?= $link ?>" target="_blank">
            <?= $targetObject ?>
        </a>
    </p>
    <span style="font-size: x-small;font-style: italic;color: grey;display: block">
        Содержание жалобы:
    </span>
    <?= $complaint->getComplaint() ?>
    <button onclick="closeComplaint('<?= $complaint->getId() ?>')" class="btn-basic d-block">Закрыть жалобу</button>
    <hr class="mt-2 mb-3">
</div>
