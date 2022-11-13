<?php

declare(strict_types=1);

/**
 * @var \app\models\Post[] $posts
 * @var int $pages
 * @var int $curPage
 * @var string $search
 * @var \app\models\User $user
 * @var \yii\web\View $this
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use src\helpers\PaginationHelper;

$this->title = 'Главная страница';
$session = Yii::$app->session;
?>
<?php if ($session->hasFlash('messageForIndex')): ?>
    <div class="alert alert-warning rounded-4 text-break mx-3" role="alert">
        <?= $session->getFlash('messageForIndex') ?>
    </div>
<?php endif ?>
<?php if ($posts): ?>
    <div class="mx-3">
        <?php if ($pages > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php if ($curPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link bg-transparent text-black x-small"
                               href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $curPage - 1 ?>">Назад</a>
                        </li>
                    <?php endif ?>
                    <?php foreach ((new PaginationHelper())->getNavPages($curPage, $pages,1,3) as $page): ?>
                        <?php if ($page === $curPage): ?>
                            <li class="page-item disabled">
                                <a class="page-link bg-secondary text-black x-small"><?= $page ?></a>
                            </li>
                            <?php continue; endif; ?>
                        <li class="page-item">
                            <a class="page-link bg-transparent text-black x-small"
                               href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $page ?>">
                                <?= $page ?>
                            </a>
                        </li>
                    <?php endforeach ?>
                    <?php if ($curPage !== $pages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-end bg-transparent text-black x-small"
                               href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $curPage + 1 ?>">
                                Вперед
                            </a>
                        </li>
                    <?php endif ?>
                    <!--Открыть определенную страницу-->
                    <li class="page-item d-none d-sm-block">
                        <form class="d-flex">
                            <?php if ($search !== null): ?>
                                <input type="hidden" name="search" value="<?= $search ?>">
                            <?php endif ?>
                            <input class="txt-input-basic ms-2 me-1 x-small w-50" name="page" type="search" placeholder="Страница"
                                   aria-label="Search"
                                   required>
                            <button class="btn-basic" type="submit">
                                &#62;
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        <?php endif ?>
        <?php foreach ($posts as $post): ?>
            <div class="card rounded-0 mx-auto mb-1">
                <div class="card-header hstack">
                    <div class="col text-muted small">
                        <?= $post->getViews() ?>
                        <img src="/assets/images/views.svg" width="18" alt="views">
                        &nbsp;
                        <?= $post->getCommentsAmount() ?>
                        <img src="/assets/images/comments.svg" width="18" alt="comments"/>
                        <?= ConstructHtml::rating($post->getRating()) ?>
                    </div>
                    <div class="col small text-end">
                        <a class="author-link" href="/users/<?= $post->getAuthor() ?>">
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
                    <div class="card-text">
                        <a class="nav-link" href="/post?id=<?= $post->getId() ?>">
                            <?= $post->getPreview($post->getBody(), 1000, '') ?>
                        </a>
                    </div>
                </div>
                <div class="hstack card-footer small">
                    <?php if ($user !== null && !$user->getIsAdmin()): ?>
                        <button type="button"
                                onclick="createComplaint('post', '<?= $post->getId() ?>')"
                                class="btn-w-img col">
                            <img src="<?= IMAGES ?>create-complaint.svg" alt="create complaint"/>
                        </button>
                    <?php endif ?>
                    <span class="text-end col">
                        <?= NormalizeData::passedTime($post->getDatetime()) ?>
                    </span>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif; ?>
