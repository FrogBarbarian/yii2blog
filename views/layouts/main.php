<?php

declare(strict_types=1);

/**
 * @var \yii\web\View $this
 */

use app\assets\AppAsset;
use app\components\SearchWidget;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= Html::csrfMetaTags() ?>
    <title><?= $this->title ?? 'Need to setup name' ?></title>
    <?php $this->head() ?>
</head>
<body class="h-100" style="background-image: url(<?= IMAGES ?>bg.jpg);">
<?php $this->beginBody() ?>
<header class="sticky-top">
    <?php
    $user = Yii::$app->user->getIdentity();
    $admin = false;
    $isGuest = $user === null;

    if (!$isGuest) {
        $admin = $user->getIsAdmin();
    }
    ?>
    <nav class="navbar navbar-expand-lg bg-secondary">
        <div class="container-fluid">
            <a class="nav-button d-flex justify-content-between mx-1 my-auto" href="/">
                <img src="<?= IMAGES ?>logo.svg" alt="logo" width="30"
                     class="d-inline-block align-text-top">
                <span class="m-auto x-large">Блог</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-1 my-auto">
                        <a class="nav-button" href="/site/about">
                            О блоге
                        </a>
                    </li>
                    <li class="nav-item mx-1 my-auto">
                        <a class="nav-button" href="/site/random" target="_blank">
                            Случайная статья
                        </a>
                    </li>
                    <?php if (!$isGuest): ?>
                        <?php if ($user->getCanWritePosts()): ?>
                            <li class="nav-item mx-1 my-auto">
                                <a class="nav-button" href="/new-post">Создать пост</a>
                            </li>
                        <?php endif ?>
                        <?php if ($admin): ?>
                            <li class="nav-item mx-1 my-auto">
                                <a class="nav-button" href="<?= ADMIN_PANEL ?>">Админ-панель</a>
                            </li>
                        <?php
                        endif;
                        echo '</ul>';
                        echo SearchWidget::widget();
                        ?>
                        <div class="nav-item mx-1 my-auto">
                            <a class="nav-button" href="/profile">
                                Профиль
                            </a>
                        </div>
                        <div class="nav-item mx-1 my-auto">
                            <a class="nav-button" href="/user/logout">
                                Выйти
                            </a>
                        </div>
                    <?php else: ?>
                        <?php
                        echo '</ul>';
                        echo SearchWidget::widget();
                        ?>
                        <div class="nav-item mx-1 my-auto">
                            <a class="nav-button" href="<?= USER_LOGIN ?>">
                                Вход
                            </a>
                        </div>
                        <div class="nav-item mx-1 my-auto">
                            <a class="nav-button" href="<?= USER_REGISTER ?>">
                                Зарегистрироваться
                            </a>
                        </div>
                    <?php endif ?>
            </div>
        </div>
    </nav>
</header>
<div class="content m-auto">
    <div id="content" class="py-1">
        <?= $content ?? '' ?>
    </div>
    <div id="modalDiv"></div>
</div>
<button id="arrowTop" hidden>
    &#8593;
</button>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
