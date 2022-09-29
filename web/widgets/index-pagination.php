<?php
/** @var int $curPage */
/** @var int $pages */

use src\services\PaginationService;
?>

<?php if ($pages > 1): ?>
    <nav>
        <ul class="pagination">
                <?php if ($curPage > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $curPage - 1 ?>">Назад</a>
                        </li>
                    <?php endif ?>
                    <?php foreach ((new PaginationService())->getNavPages($curPage, $pages) as $page): ?>
                        <?php if ($page == $curPage): ?>
                            <li class="page-item disabled">
                                <a class="page-link"><?= $page ?></a>
                            </li>
                        <?php continue; endif; ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a></li>
                    <?php endforeach ?>
                    <?php if ($curPage !== $pages): ?>
                        <li class="page-item">
                            <a class="page-link rounded-end" href="?page=<?= $curPage + 1 ?>">Вперед</a>
                        </li>
                <?php endif ?>
            <li class="page-item d-none d-sm-block">
                <form class="d-flex" action="?page=">
                    <input class="form-control ms-2 me-1" name="page" type="text" placeholder="Страница" aria-label="Search"
                           style="max-width: 115px">
                    <button class="page-link" type="submit">Перейти</button>
                </form>
            </li>
        </ul>
    </nav>
<?php endif ?>