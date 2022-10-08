<?php

/**
 * @var \app\models\Post[] $posts
 * @var string $page
 * @var string $search
 */

use src\helpers\ConstructHtml;

$curPage = intval($page);
$this->title = 'Главная страница';
?>
    <?php if ($search !== null): ?>
        <div class="alert alert-warning rounded-5 small mt-1 mx-1" role="alert">
            <?php if ($posts): ?>
                Результат поиска по фразе '<?= $search ?>'.
            <?php else: ?>
                К сожалению, по запросу '<?= $search ?>' ничего не найдено.
            <?php endif ?>
        </div>
    <?php endif ?>
    <?php if ($posts): ?>
        <div class="mx-3 py-5">
            <?php require 'widgets/index-pagination.php' ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 rounded-4 mx-auto" style="border-color: #656560;border-width: medium;">
                    <div class="card-header">
                        Опубликован: <b><?= $post->getDate() ?></b>.
                        Просмотров: <?= $post->getViews() ?>.
                        Автор - <?= $post->getAuthor() ?>
                        <?= ConstructHtml::rating($post->getRating()) ?>
                        <!--TODO: Отображение количества комментариев (если есть)-->
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a class="nav-link" href="/post?id=<?= $post->getId() ?>">
                                <?= $post->getTitle() ?>
                            </a>
                        </h5>
                        <p class="card-text">
                            <a class="nav-link" href="/post?id=<?= $post->getId() ?>">
                                <?= $post->getPreview($post->getBody()) ?>
                            </a>
                        </p>
                    </div>
                </div>
            <?php endforeach ?>
            <?php require 'widgets/index-pagination.php' ?>
        </div>
    <?php endif; ?>
