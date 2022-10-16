<?php
/**
 * @var \app\models\Tag[] $tags
 */
?>

<span class="admin-panel-header">Обзор тегов</span>
<hr style="color: #14376c">
<small>
    Ниже представлен список всех сохраненных тегов, справа указанно количество использований,
    если использований 0, то тег рекомендуется удалить.
</small>
<div class="mt-3"></div>
<?php foreach ($tags as $tag): ?>
<span class="tag-card"><?= $tag->getTag() ?></span>

<?php endforeach ?>
