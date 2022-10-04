<?php

$this->title = 'Панель администратора';
$page = Yii::$app->requestedRoute;
?>

<div class="row mt-1 rounded-2" style="height: 90vh">
    <div class="col-3 p-3 text-white bg-dark">
        <a class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <span class="fs-4">Панель</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?= ADMIN_PANEL ?>" class="nav-link text-white" aria-current="page">
                    Обзор
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= ADMIN_PANEL ?>/posts" class="nav-link text-white">
                    Посты пользователей
                </a>
            </li>
            <li>
                <a href="<?= ADMIN_PANEL ?>/complaints" class="nav-link text-white me-2">
                    Жалобы
                </a>
            </li>
        </ul>
    </div>

    <div class="col bg-white">
            <?php switch ($page) {
                case 'admin/index':
                    require 'overview.php';
                    break;
                case 'admin/posts':
                    require 'posts.php';
                    break;
                default:
                    require 'complaints.php';
                    break;
            } ?>
    </div>
</div>
