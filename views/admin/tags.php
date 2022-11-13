<?php

declare(strict_types=1);

/**
 * @var \app\models\Tag[] $unusedTags
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 * @var string $sortParam
 * @var int $sortOrder
 * @var \yii\web\View $this
 */

use app\components\AdminMenuWidget;
use app\components\AdminFilterOptionsWidget;

$this->title = 'Теги';
echo AdminMenuWidget::widget(['amountUnusedTags' => count($unusedTags)]);
$this->registerJsFile('@js/admin/tags.js');
?>
<span class="admin-panel-header">Обзор тегов</span>
<hr>
<h6>
    На этой странице представлен список всех сохраненных тегов, справа указанно количество использований.
    Не использующиеся теги рекомендуется удалять.
</h6>
<?php if ($unusedTags !== []): ?>
    <hr>
    <p class="admin-panel-info">
        Не использующиеся теги.
        <span class="x-small text-danger">
            (Для удаления нажмите на тег)
        </span>
    </p>
    <?php foreach ($unusedTags as $tag): ?>
        <span id="tag_<?= $tag->getId() ?>" class="tag-card text-center text-black bg-danger"
              data-bs-toggle="collapse"
              href="#deleteTag_<?= $tag->getId() ?>" role="button" aria-expanded="false">
            <?= $tag->getTag() ?>
            <div onclick="deleteTag('<?= $tag->getId() ?>')" class="collapse rounded-1 bg-white"
                 id="deleteTag_<?= $tag->getId() ?>">
                    Удалить
            </div>
        </span>
    <?php endforeach ?>
    <hr>
<?php endif ?>
<div class="sort-panel">
    <span class="pe-2">Сортировать по:</span>
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
    <?= AdminFilterOptionsWidget::widget([
        'offset' => $offset,
        'pages' => $pages,
        'curPage' => $curPage,
        'sortParam' => $sortParam,
        'sortOrder' => $sortOrder,
        'tab' => 'tags',
    ]) ?>
    <div class="my-3" id="objects"></div>
