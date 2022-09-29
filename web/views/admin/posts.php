<?php
/** @var \app\models\PostTmp[] $posts */

?>
<h5 class="card-title">Посты пользователей</h5>

<?php if ($posts): ?>
    Список постов пользователей к рассмотрению:
    <br>
    <?php foreach ($posts as $post): ?>
        <br>
        <a href="<?=ADMIN_PANEL?>/user-post?id=<?=$post->getId()?>" data-toggle="tooltip" data-placement="top"
           title="<?=$post->getTitle()?>">
            <?=$post->getPreview($post->getTitle(), 10, '') ?> | Автор:
            <?=$post->getAuthor() ?> |
            <?=$post->getIsNew() ? 'Новый' : 'Отредактированный' ?>
        </a>

        <hr>
    <?php endforeach ?>
<?php else: ?>
    В данный момент нет постов для одобрения.
<?php endif ?>
