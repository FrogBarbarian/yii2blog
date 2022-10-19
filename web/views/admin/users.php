<?php
declare(strict_types=1);

/**
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 */

use app\components\AdminFilterOptionsWidget;
use app\components\AdminMenuWidget;

echo AdminMenuWidget::widget();
?>
<script src="../../assets/js/admin/users.js"></script>
<span class="admin-panel-header">Пользователи</span>
<hr style="color: #14376c">
<p class="admin-panel-info">
    Ниже представлен список всех пользователей.
</p>

<div class="sort-panel">
    <span style="padding-right: 10px">Сортировать по:</span>
    <span class="sort" onclick="sort('id')">Дате регистрации<span id="arrow_id"
                                                                  style="color: white">&darr;</span></span>
    <span class="sort" onclick="sort('username')">Алфавиту<span id="arrow_username">&darr;</span></span>
    <span class="sort" onclick="sort('is_admin')">Типу учетной записи<span id="arrow_is_admin">&darr;</span></span>
    <?= AdminFilterOptionsWidget::widget([
        'offset' => $offset,
        'pages' => $pages,
        'curPage' => $curPage,
        'tab' => 'users',
    ]) ?>
    <div class="my-3 posts-grid justify-content-around" id="objects"></div>
