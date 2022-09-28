<?php
/** @var \app\models\PostTmp[] $posts */

?>
<h5 class="card-title">Посты пользователей</h5>

<?php if ($posts): ?>
    Список постов пользователей к рассмотрению:
    <br>
    <?php foreach ($posts as $post): ?>
        <br>
        <a href="user-post?id=<?=$post->getId()?>" data-toggle="tooltip" data-placement="top"
           title="<?=$post->getTitle()?>">
            <?=$post->getPreview($post->getTitle(), 10, '') ?> | Автор:
            <?=$post->getAuthor() ?> |
            <?=$post->getIsNew() ? 'Новый' : 'Отредактированный' ?>
        </a>

        <hr>
    <?php endforeach ?>
<?php endif ?>
