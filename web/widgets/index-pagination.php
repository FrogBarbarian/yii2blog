<?php
/** @var int $curPage */
/** @var int $pages */
/** @var string $search */

use src\services\PaginationService;
?>

<?php if ($pages > 1): ?>
    <nav>
        <ul class="pagination">
                <?php if ($curPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?<?php if ($search !== null) echo "search=$search&"?>page=<?=$curPage - 1 ?>">Назад</a>
                        </li>
                    <?php endif ?>
                    <?php foreach ((new PaginationService())->getNavPages($curPage, $pages) as $page): ?>
                        <?php if ($page == $curPage): ?>
                            <li class="page-item disabled">
                                <a class="page-link"><?=$page ?></a>
                            </li>
                        <?php continue; endif; ?>
                        <li class="page-item"><a class="page-link" href="?<?php if ($search !== null) echo "search=$search&"?>page=<?= $page ?>"><?= $page ?></a></li>
                    <?php endforeach ?>
                    <?php if ($curPage !== $pages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-end"
                               href="?<?php if ($search !== null) echo "search=$search&"?>page=<?= $curPage + 1 ?>">
                                Вперед
                            </a>
                        </li>
                <?php endif ?>
            <!--Открыть определенную страницу-->
            <li class="page-item d-none d-sm-block">
                <form class="d-flex">
                    <?php if ($search !== null): ?>
                        <input type="hidden" name="search" value="<?=$search?>">
                    <?php endif ?>
                    <input class="form-control ms-2 me-1" name="page" type="search" placeholder="Страница" aria-label="Search"
                           style="max-width: 115px;" required>
                    <button class="page-link" type="submit">
                        <img src="../../assets/images/arrow-right.svg" alt="Logo" width="16" height="16">
                    </button>
                </form>
            </li>
        </ul>
    </nav>
<?php endif ?>