<?php
/**
 * @var \app\models\Post[] $posts
 * @var string $page
 * @var string $search
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;

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
    <div style="margin-left: 10%;margin-right: 10%;">
        <?php require 'widgets/index-pagination.php' ?>
        <?php foreach ($posts as $post): ?>
            <div class="card rounded-0 mx-auto mb-1">
                <div class="card-header hstack" >
                    <div class="col" style="font-size:small;text-align:start;">
                        <?= $post->getViews() . ' ' . NormalizeData::wordForm($post->getViews(), 'просмотров', 'просмотр', 'просмотра') ?>
                        <?= ConstructHtml::rating($post->getRating()) ?>
                        <?php if ($post->getCommentsAmount() > 0): ?>
                            &nbsp;
                            <?= $post->getCommentsAmount() ?>
                            <img src = "/assets/images/comments.svg" width="18" alt="comments"/>
                        <?php endif ?>
                    </div>
                    <div class="col" style="font-size:small;text-align:end;">
                        <a class="nav-link" href="/user?id=<?= $post->getAuthorId() ?>" style="color: dodgerblue">
                            <?= $post->getAuthor() ?>
                        </a>
                    </div>
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
                <div class="card-footer text-end" style="font-size: small">
                    <?= NormalizeData::date($post->getDate()) ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif; ?>
