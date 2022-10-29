<?php
/**
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 * @var string $tab
 * @var string $sortParam
 * @var int $sortOrder
 */

declare(strict_types=1);

use src\helpers\PaginationHelper;
?>
<form>
    <label for="setOffset">Выводить по: </label>
    <input id="setOffset" type="text" name="offset" placeholder="<?= $offset !== 0 ? $offset : '' ?>"
           style="width: 50px; height: 20px">
    <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
    <button type="submit"
            style="width: 20px;height: 20px;display: inline-flex;justify-content: center;align-items: center;">
        &#8626;
    </button>
    <span onclick="setOffsetInput(10)" class="complaint-link" style="font-size: small">10</span>
    <span onclick="setOffsetInput(25)" class="complaint-link" style="font-size: small">25</span>
    <span onclick="setOffsetInput(50)" class="complaint-link" style="font-size: small">50</span>
</form>
</div>
<?php if ($pages > 1): ?>
    <?php if ($curPage <= $pages): ?>
        <ul class="pagination mt-1">
            <?php foreach ((new PaginationHelper())->getNavPages($curPage, $pages) as $page): ?>
                <?php if ($page === $curPage): ?>
                    <li class="page-item disabled">
                        <a class="page-link"
                           style="background-color: rgba(0,0,0,0);color: #888888;font-size: small"><?= $page ?></a>
                    </li>
                    <?php continue; endif; ?>
                <li class="page-item">
                    <form>
                        <input type="hidden" name="page" value="<?= $page ?>">
                        <input type="hidden" name="offset" value="<?= $offset ?>">
                        <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
                        <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
                        <button class="page-link" style="background-color: rgba(0,0,0,0);color: #000000;font-size: small" type="submit"><?= $page ?></button>
                    </form>
                </li>
            <?php endforeach ?>
            <form class="d-flex">
                <input name="page" type="search" placeholder="<?= $curPage ?>"
                       style="max-width: 50px;background-color: rgba(0,0,0,0);color: #000000;font-size: small;border-color: #dee2e6"
                       required>
                <input type="hidden" name="offset" value="<?= $offset ?>">
                <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
                <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
                <button class="page-link" type="submit"
                        style="background-color: rgba(0,0,0,0);color: #000000;font-size: small">
                    &rarr;
                </button>
            </form>
        </ul>
    <?php else: ?>
        <p class="admin-panel-info my-2">
            Введены не верные данные,
            <a href="<?= ADMIN_PANEL . "/$tab"?>">сбросить?</a>
        </p>
    <?php endif ?>
<?php endif ?>
