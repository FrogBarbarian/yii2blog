<?php
/**
 * @var int $amountTmpPosts
 * @var int $amountUnusedTags
 * @var int $amountComplaints
 * @var \yii\web\View $this
 */

declare(strict_types=1);

$this->title = 'Панель администратора';
$this->registerJsFile('@js/admin/main.js');

?>
<div class="row">
    <div class="col-4 bg-dark text-white">
        <span class="pt-2 fs-4 text-center d-block ">Админ-панель</span>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="<?= ADMIN_PANEL ?>" class="nav-link text-white me-2">
                    Статистика
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/users" class="nav-link text-white me-2">
                    Пользователи
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/tags" class="nav-link text-white me-2">
                    Теги
                    <?php if ($amountUnusedTags > 0): ?>
                        <span class="badge text-bg-secondary">
                            <?= $amountUnusedTags ?>
                        </span>
                    <?php endif ?>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/posts" class="nav-link text-white me-2">
                    Посты
                    <?php if ($amountTmpPosts > 0): ?>
                        <span class="badge text-bg-secondary">
                            <?= $amountTmpPosts ?>
                        </span>
                    <?php endif ?>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/complaints" class="nav-link text-white me-2">
                    Жалобы пользователей
                    <?php if ($amountComplaints > 0): ?>
                        <span class="badge text-bg-secondary">
                            <?= $amountComplaints ?>
                        </span>
                    <?php endif ?>
                </a>
            </li>
        </ul>
    </div>

    <div class="col bg-white">
