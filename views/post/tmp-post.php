<?php

declare(strict_types=1);

/**
 * @var \app\models\TmpPost $tmpPost
 * @var \yii\web\View $this
 */

$this->title = 'Временный пост';
$this->registerJsFile('@js/mini/tmp-post.js');
?>

<div class="window-basic text-break">
    <h5><?= $tmpPost->getTitle()?></h5>
    <hr>
    <?= $tmpPost->getBody() ?>
    <hr>
    Используемые теги:
    <?php foreach ($tmpPost->getTagsArray() as $tag): ?>
    <span class="tag-card"><?= $tag ?></span>
    <?php endforeach ?>
    <hr>
    <button type="button" class="btn-basic" data-bs-toggle="modal" data-bs-target="#deleteModal">
        Удалить пост
    </button>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Удалить пост?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Изменения нельзя откатить, Ваш пост будет навсегда удален.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-basic" data-bs-dismiss="modal">Отмена</button>
                <button id="deleteTempPostButton" type="button" class="btn-basic">Удалить</button>
            </div>
        </div>
    </div>
</div>
