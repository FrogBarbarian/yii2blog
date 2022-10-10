<?php
/** @var int $curPage */
/** @var int $pages */

/** @var string $search */

use src\helpers\PaginationHelper;

?>

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
