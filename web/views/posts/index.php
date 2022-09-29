<?php

/** @var \app\models\Post[] $posts */
/** @var int $pages */
/** @var string $page */


$curPage = intval($page);
$this->title = 'Главная страница';
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vw;margin-right: 1vw;">
    <?php if ($posts): ?>
        <div class="mx-3 py-5">
            <?php require 'widgets/index-pagination.php'?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 rounded-4 mx-auto" style="border-color: #656560;border-width: medium;">
                    <div class="card-header">Опубликован: <b><?=$post->getDate()?></b>. Просмотров: <?=$post->getViews()?>. Автор - <?=$post->getAuthor()?></div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a class="nav-link" href="/post?id=<?=$post->getId()?>">
                                <?=$post->getTitle()?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <a class="nav-link" href="/post?id=<?=$post->getId()?>">
                                <?=$post->getPreview($post->getBody())?>
                            </a>
                        </p>
                    </div>
                </div>
            <?php endforeach ?>
            <?php require 'widgets/index-pagination.php'?>
        </div>
    <?php endif; ?>
</div>
