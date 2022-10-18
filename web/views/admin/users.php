<?php
/**
 * @var \app\models\User[] $users
 */

use app\components\AdminMenuWidget;

echo AdminMenuWidget::widget();
?>
<script src="../../assets/js/admin/users.js"></script>
<span class="admin-panel-header">Пользователи</span>
<hr style="color: #14376c">
<small>
    Ниже представлен список всех пользователей.
</small>
<div class="sort-panel">
    <span style="padding-right: 10px">Сортировать по:</span>
    <span class="sort" onclick="sort('id')">Дате регистрации<span id="arrow_id" style="color: white">&darr;</span></span>
    <span class="sort" onclick="sort('owner')">Алфавиту<span id="arrow_username">&darr;</span></span>
    <span class="sort" onclick="sort('posts')">Количеству постов<span id="arrow_posts">&darr;</span></span>
    <span class="sort" onclick="sort('comments')">Количеству комментариев<span id="arrow_comments">&darr;</span></span>
    <span class="sort" onclick="sort('rating')">Рейтингу<span id="arrow_rating">&darr;</span></span>
</div>
<div class="my-3 text-center" id="objects"></div>

