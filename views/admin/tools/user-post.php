<?php

declare(strict_types=1);

/**
 * @var \app\models\TmpPost $post
 * @var \app\models\Post|null $originalPost
 * @var \yii\web\View $this
 */

use src\helpers\NormalizeData;

$this->title = "Пост пользователя {$post->getAuthor()}";
$this->registerJsFile('@js/utilities/notice.js');
$this->registerJsFile('@js/admin/post-approval.js');
?>

<div class="window-basic card">
    <div class="card-body">
        <h5 class="card-title"><?= $post->getTitle() ?></h5>
        <p class="card-text"><?= $post->getBody() ?></p>
    </div>
    <div class="card-footer">
        <div>
            <?php foreach ($post->getTagsArray() as $tag): ?>
                <span class="tag-card"><?= $tag ?></span>
            <?php endforeach ?>
            <hr>
            <a class="author-link" target="_blank" href="/users/<?= $post->getAuthor() ?>">
                <?= $post->getAuthor() ?>
            </a>
            отправил <?= NormalizeData::passedTime($post->getDatetime()) ?>

        </div>
        <hr>
        <button id="postApproveButton" class="btn-basic">Одобрить</button>
        <button id="postDisapproveButton" class="btn-basic">Отказать</button>
    </div>
</div>
<?php if ($post->getIsNew() === false): ?>
    <div class="window-basic card mt-2">
        <h4 class="card-header">Оригинал поста</h4>
        <div class="card-body">
            <h5 class="card-title"><?= $originalPost->getTitle() ?></h5>
            <p class="card-text"><?= $originalPost->getBody() ?></p>
        </div>
        <div class="card-footer">
            <?php foreach ($originalPost->getTagsArray() as $tag): ?>
                <span class="tag-card"><?= $tag ?></span>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
