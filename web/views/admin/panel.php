<?php
/**
 * @var string $tab
 * @var \app\models\Tag[] $unusedTags
 */

$this->title = 'Панель администратора';
?>
<script src="../../assets/js/admin/main.js"></script>
<div class="row">
    <div class="col-4 bg-dark text-white">
            <span class="fs-4 text-center d-flex">Панель</span>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="<?= ADMIN_PANEL ?>?tab=users" class="nav-link text-white me-2">
                    Пользователи
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>?tab=tags" class="nav-link text-white me-2">
                     Обзор тегов
                    <?php if ($unusedTags !== []): ?>
                        <span class="badge text-bg-secondary">
                            <?= count($unusedTags) ?>
                        </span>
                    <?php endif ?>
                </a>
            </li>
        </ul>
    </div>

    <div class="col bg-white">
            <?php
            try {
                require "tabs/$tab.php";
            } catch (Exception) {
                require 'tabs/overview.php';
            }?>
    </div>
</div>
