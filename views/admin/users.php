<?php
declare(strict_types=1);

/**
 * @var int $offset
 * @var int $pages
 * @var int $curPage
 * @var string $sortParam
 * @var int $sortOrder
 * @var \yii\web\View $this
 */

use app\components\AdminFilterOptionsWidget;
use app\components\AdminMenuWidget;

echo AdminMenuWidget::widget();
$this->registerJsFile('@js/admin/users.js');
?>
<span class="admin-panel-header">Пользователи</span>
<hr style="color: #14376c">
<h6>
    На этой странице представлен список всех пользователей.
</h6>
<hr>
<div class="sort-panel">
    <span style="padding-right: 10px">Сортировать по:</span>
    <span class="sort" onclick="sort('id')">
        Дате регистрации
        <span id="arrow_id">
            &darr;
        </span>
    </span>
    <span class="sort" onclick="sort('username')">
        Алфавиту
        <span id="arrow_username">
            &darr;
        </span>
    </span>
    <span class="sort" onclick="sort('is_admin')">
        Типу учетной записи
        <span id="arrow_is_admin">
            &darr;
        </span>
    </span>
    <?= AdminFilterOptionsWidget::widget([
        'offset' => $offset,
        'pages' => $pages,
        'curPage' => $curPage,
        'sortParam' => $sortParam,
        'sortOrder' => $sortOrder,
        'tab' => 'users',
    ]) ?>
    <div class="my-3 posts-grid" id="objects"></div>
