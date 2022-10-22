<?php
/**
 * @var \app\models\Post[] $posts
 * @var int $pages
 * @var int $curPage
 * @var string $search
 * @var \app\models\User $user
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use src\helpers\PaginationHelper;

$this->title = 'Главная страница';
$session = Yii::$app->session;

?>
<?php if ($session->hasFlash('messageForIndex')): ?>
    <div class="alert alert-warning rounded-4" role="alert" style="margin-left: 10%;margin-right: 10%;margin-top: 5%">
        <?= $session->getFlash('messageForIndex') ?>
    </div>
<?php endif ?>
<?php if ($posts): ?>
    <div style="margin-left: 5%;margin-right: 5%;">
        <?php if ($pages > 1): ?>
            <nav>
                <ul class="pagination">
                    <?php if ($curPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" style="background-color: rgba(0,0,0,0);color: #000000;font-size: small"
                               href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $curPage - 1 ?>">Назад</a>
                        </li>
                    <?php endif ?>
                    <?php foreach ((new PaginationHelper())->getNavPages($curPage, $pages) as $page): ?>
                        <?php if ($page === $curPage): ?>
                            <li class="page-item disabled">
                                <a class="page-link"
                                   style="background-color: rgba(0,0,0,0);color: #888888;font-size: small"><?= $page ?></a>
                            </li>
                            <?php continue; endif; ?>
                        <li class="page-item"><a class="page-link"
                                                 style="background-color: rgba(0,0,0,0);color: #000000;font-size: small"
                                                 href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $page ?>"><?= $page ?></a>
                        </li>
                    <?php endforeach ?>
                    <?php if ($curPage !== $pages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-end"
                               style="background-color: rgba(0,0,0,0);color: #000000;font-size: small"
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
                            <input class="form-control ms-2 me-1" name="page" type="search" placeholder="Страница"
                                   aria-label="Search"
                                   style="max-width: 85px;background-color: rgba(0,0,0,0);color: #000000;font-size: small;border-color: #dee2e6"
                                   required>
                            <button class="page-link" type="submit"
                                    style="background-color: rgba(0,0,0,0);color: #000000;font-size: small">
                                <img src="/assets/images/arrow-right.svg" alt="Page open" width="16" height="16">
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        <?php endif ?>
        <?php foreach ($posts as $post): ?>
            <div class="card rounded-0 mx-auto mb-1">
                <div class="card-header hstack">
                    <div class="col text-muted" style="font-size:small;text-align:start;">
                        <?= $post->getViews() ?>
                        <img src="/assets/images/views.svg" width="18" alt="views">
                        &nbsp;
                        <?= $post->getCommentsAmount() ?>
                        <img src="/assets/images/comments.svg" width="18" alt="comments"/>
                        <?= ConstructHtml::rating($post->getRating()) ?>
                    </div>
                    <div class="col" style="font-size:small;text-align:end;">
                        <a class="author-link" href="/user?id=<?= $post->getAuthorId() ?>">
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
                <div class="hstack card-footer" style="font-size: small">
                    <?php if ($user !== null && !$user->getIsAdmin()): ?>
                        <button type="button" style="max-width: 48px"
                                onclick="createComplaint('post', <?= $post->getId() ?>, <?= $user->getId() ?>)"
                                class="btn btn-light col">
                            <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
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
