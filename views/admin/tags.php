<?php
/**
 * @var \app\models\Tag[] $unusedTags
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 * @var string $sortParam
 * @var int $sortOrder
 */

declare(strict_types = 1);

use app\components\AdminMenuWidget;
use app\components\PageSwitcherWidget;

echo AdminMenuWidget::widget(['amountUnusedTags' => count($unusedTags)]);
?>

<script src="../../web/assets/js/admin/tags.js"></script>
<span class="admin-panel-header">Обзор тегов</span>
<hr style="color: #14376c">
<h6 >
    На этой странице представлен список всех сохраненных тегов, справа указанно количество использований.
    Не использующиеся теги рекомендуется удалять.
</h6>
<?php if ($unusedTags !== []): ?>
    <hr>
    <p class="admin-panel-info">
        Не использующиеся теги.
        <span style="font-size: x-small; color: red">
            (Для удаления нажмите на тег)
        </span>
    </p>

    <?php foreach ($unusedTags as $tag): ?>
        <span id="tag_<?= $tag->getId() ?>" class="tag-card text-center" style="background-color: #c85050;color: black"
              data-bs-toggle="collapse"
              href="#deleteTag_<?= $tag->getId() ?>" role="button" aria-expanded="false">
            <?= $tag->getTag() ?>
            <div onclick="deleteTag('<?= $tag->getId() ?>')" class="collapse rounded-1"
                 id="deleteTag_<?= $tag->getId() ?>" style="background-color: white">
                    Удалить
            </div>
        </span>
    <?php endforeach ?>
    <hr>
<?php endif ?>
<div class="sort-panel">
    <span style="padding-right: 10px">Сортировать по:</span>
    <span class="sort" onclick="sort('id')">
        Новизне
        <span id="arrow_id">
            &darr;
        </span>
    </span>
    <span class="sort" onclick="sort('tag')">
        Алфавиту
        <span id="arrow_tag">
            &darr;
        </span>
    </span>
    <span class="sort" onclick="sort('amount_of_uses')">
        Количеству использований
        <span id="arrow_amount_of_uses">
            &darr;
        </span>
    </span>
    <?= PageSwitcherWidget::widget([
        'offset' => $offset,
        'pages' => $pages,
        'curPage' => $curPage,
        'sortParam' => $sortParam,
        'sortOrder' => $sortOrder,
        'tab' => 'tags',
    ]) ?>
<div class="my-3" id="objects"></div>