<?php
declare(strict_types = 1);
/**
 * @var \app\models\Tag[] $tags
 * @var \app\models\Tag[] $unusedTags
 */

use app\components\AdminMenuWidget;

echo AdminMenuWidget::widget();
?>

<script src="../../assets/js/admin/tags.js"></script>
<span class="admin-panel-header">Обзор тегов</span>
<hr style="color: #14376c">
<small>
    Ниже представлен список всех сохраненных тегов, справа указанно количество использований.
    Не использующиеся теги рекомендуется удалять.
</small>
<?php if ($unusedTags !== []): ?>
    <hr>
    <p class="mt-2">
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
    <span class="sort" onclick="sort('id')">Новизне<span id="arrow_id" style="color: white">&darr;</span></span>
    <span class="sort" onclick="sort('tag')">Алфавиту<span id="arrow_tag">&darr;</span></span>
    <span class="sort" onclick="sort('amount_of_uses')">Количеству использований<span id="arrow_amount_of_uses">&darr;</span></span>
</div>
<div class="my-3" id="objects"></div>