<?php
/**
 * @var \app\models\TmpPost $tmpPosts
 */

$this->title = 'Панель администратора';
?>
<script src="../../assets/js/admin/main.js"></script>
<div class="row">
    <div class="col-4 bg-dark text-white">
        <span class="pt-2 fs-4 text-center d-block ">Админ-панель</span>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="<?= ADMIN_PANEL ?>" class="nav-link text-white me-2">
                    Обзор
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
                    <!--                    --><?php //if ($unusedTags !== []): ?>
                    <!--                        <span class="badge text-bg-secondary">-->
                    <!--                            --><?php // count($unusedTags) ?>
                    <!--                        </span>-->
                    <!--                    --><?php //endif ?>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/posts" class="nav-link text-white me-2">
                    Посты<?php if ($tmpPosts !== []): ?>
                        <span class="badge text-bg-secondary">
                            <?= count($tmpPosts) ?>
                        </span>
                    <?php endif ?>
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/complaints" class="nav-link text-white me-2">
                    Жалобы пользователей
                    <!--                    --><?php //if (count($complaints) > 0): ?>
                    <!--                        <span class="badge text-bg-secondary">-->
                    <!--                            --><?php //= count($complaints) ?>
                    <!--                        </span>-->
                    <!--                    --><?php //endif ?>
                </a>
            </li>
        </ul>
    </div>

    <div class="col bg-white">
