<?php

/** @var \app\models\Post[] $posts */

$this->title = 'Главная страница';
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <?php if ($posts): ?>
        <div class="mx-3 py-5">
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 rounded-4 mx-auto" style="border-color: #656560;border-width: medium;">
                    <div class="card-header">Опубликован: <b><?=$post->getDate()?></b>. Просмотров: <?=$post->getViews()?>. Автор - <?=$post->getAuthor()?></div>
                    <div class="card-body">
                        <h5 class="card-title"><?=$post->getTitle()?></h5>
                        <p class="card-text"><?=$post->getPreview($post->getBody())?></p>
                    </div>
                    <div class="card-footer">
                        <a type="button" class="btn" href="/post?id=<?=$post->getId()?>">
                            Посмотреть
                        </a>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <div class="text-center mx-auto" style="padding-top: 100px">
            <h2 style="color: #416e2f;">Кажется, еще нет ни единого поста...</h2>
            <h3 style="color: #416e2f;font-weight: 400">С этим надо что то делать.</h3>
        </div>
    <?php endif; ?>
</div>
