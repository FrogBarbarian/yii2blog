<?php

declare(strict_types=1);

/**
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 * @var string $tab
 * @var string $sortParam
 * @var int $sortOrder
 */

use src\helpers\PaginationHelper;
?>
<form>
    <label for="setOffset">Выводить по: </label>
    <input id="setOffset" type="text" name="offset" placeholder="<?= $offset !== 0 ? $offset : '' ?>">
    <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
    <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
    <button id="submitOffset" type="submit">
        &#8626;
    </button>
    <span onclick="setOffsetInput(10)" class="complaint-link small">10</span>
    <span onclick="setOffsetInput(25)" class="complaint-link small">25</span>
    <span onclick="setOffsetInput(50)" class="complaint-link small">50</span>
</form>
</div>
<?php if ($pages > 1): ?>
    <?php if ($curPage <= $pages): ?>
        <ul class="pagination mt-1">
            <?php foreach ((new PaginationHelper())->getNavPages($curPage, $pages) as $page): ?>
                <?php if ($page === $curPage): ?>
                    <li class="page-item disabled">
                        <a class="btn-basic a-btn small bg-opacity-10 bg-white text-muted c-default"><?= $page ?></a>
                    </li>
                    <?php continue; endif; ?>
                <li class="page-item">
                    <form>
                        <input type="hidden" name="page" value="<?= $page ?>">
                        <input type="hidden" name="offset" value="<?= $offset ?>">
                        <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
                        <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
                        <button class="btn-basic a-btn small" type="submit"><?= $page ?></button>
                    </form>
                </li>
            <?php endforeach ?>
            <form class="d-flex w-25">
                <label class="w-50">
                    <input name="page" type="search" placeholder="<?= $curPage ?>" class="small border-light w-100" required>
                </label>
                <input type="hidden" name="offset" value="<?= $offset ?>">
                <input type="hidden" name="sortParam" value="<?= $sortParam ?>">
                <input type="hidden" name="sortOrder" value="<?= $sortOrder ?>">
                <button class="btn-basic a-btn small" type="submit">
                    &rarr;
                </button>
            </form>
        </ul>
    <?php else: ?>
        <p class="admin-panel-info my-2">
            Введены не верные данные,
            <a href="<?= ADMIN_PANEL . "/$tab" ?>">сбросить?</a>
        </p>
    <?php endif ?>
<?php endif ?>
