<?php
/**
 * @var \app\models\Post[] $posts
 * @var int $pages
 * @var string $page
 * @var string $search
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use src\helpers\PaginationHelper;

$session = Yii::$app->session;
$curPage = (int)$page;
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
                        <?php if ($page == $curPage): ?>
                            <li class="page-item disabled">
                                <a class="page-link" style="background-color: rgba(0,0,0,0);color: #323232;font-size: small"><?= $page ?></a>
                            </li>
                            <?php continue; endif; ?>
                        <li class="page-item"><a class="page-link" style="background-color: rgba(0,0,0,0);color: #000000;font-size: small"
                                                 href="?<?php if ($search !== null) echo "search=$search&" ?>page=<?= $page ?>"><?= $page ?></a>
                        </li>
                    <?php endforeach ?>
                    <?php if ($curPage !== $pages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-end" style="background-color: rgba(0,0,0,0);color: #000000;font-size: small"
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
                                   style="max-width: 85px;background-color: rgba(0,0,0,0);color: #000000;font-size: small;border-color: #dee2e6" required>
                            <button class="page-link" type="submit" style="background-color: rgba(0,0,0,0);color: #000000;font-size: small">
                                <img src="/assets/images/arrow-right.svg" alt="Page open" width="16" height="16">
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        <?php endif ?>
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
                <div class="hstack card-footer" style="font-size: small">
                    <?php if ($session->has('login') && !$session->has('admin')): ?>
                        <button type="button" style="max-width: 48px"
                                onclick="createComplaint('post', <?= $post->getId() ?>, <?= $session['id'] ?>)"
                                class="btn btn-light col">
                            <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
                        </button>
                    <?php endif ?>
                    <span class="text-end col">
                        <?= NormalizeData::date($post->getDate()) ?>
                    </span>
                </div>
            </div>
        <?php endforeach ?>
    </div>
<?php endif; ?>
