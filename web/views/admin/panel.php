<?php

$this->title = 'Панель администратора';
$page = Yii::$app->requestedRoute;
?>

<div class="container-fluid row mt-5 rounded-2" style="width: 100%;height: 95vh">
    <div class="col nav flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 25%;height: 100%">
        <a class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Панель</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?=ADMIN_PANEL?>" class="nav-link text-white" aria-current="page">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Обзор
                </a>
            </li>
            <li class="nav-item">
                <a href="<?=ADMIN_PANEL?>/posts" class="nav-link text-white">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#speedometer2"></use>
                    </svg>
                    Посты пользователей
                </a>
            </li>
            <li>
                <a href="<?=ADMIN_PANEL?>/complaints" class="nav-link text-white me-2">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#table"></use>
                    </svg>
                    Жалобы
                </a>
            </li>
        </ul>
    </div>

    <div class="col card" style="width: 75%;height: 100%">
        <div class="card-body">
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
</div>
}
