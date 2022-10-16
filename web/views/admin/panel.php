<?php
/**
 * @var string $tab
 */

$this->title = 'Панель администратора';
?>

<div class="row">
    <div class="col-4 bg-dark text-white">
            <span class="fs-4 text-center d-flex">Панель</span>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li>
                <a href="<?= ADMIN_PANEL ?>?tab=tags" class="nav-link text-white me-2">
                     Обзор тегов
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
