<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= yii\helpers\Html::csrfMetaTags() ?>
    <title><?= $this->title ?? 'Need to setup name' ?></title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="../../assets/css/main.css" />
    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/jquery.js"></script>
    <script src="../../assets/js/main.js"></script>
</head>
<body style="background-image: url('../../assets/images/background.webp');height: 100%">
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg" style="background-color: rgb(104,102,104);">
        <div class="container-fluid">
            <a class="nav-button d-flex justify-content-between mx-1 my-auto" href="/">
                <img src="../../assets/images/logo.svg" alt="logo" width="30"
                     class="d-inline-block align-text-top">
                <span class="m-auto" style="font-size: x-large">Блог</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item mx-1 my-auto">
                        <a onclick="randomPost()" class="nav-button" style="cursor: pointer">
                            Случайная статья
                        </a>
                    </li>
                    <?php if (isset(Yii::$app->session['login'])): ?>
                        <li class="nav-item mx-1 my-auto">
                            <a class="nav-button" href="/new-post">Создать пост</a>
                        </li>
                        <?php if (isset(Yii::$app->session['admin'])): ?>
                            <li class="nav-item mx-1 my-auto">
                                <a class="nav-button" href="<?= ADMIN_PANEL ?>">Админ-панель</a>
                            </li>
                        <?php
                        endif;
                        echo '</ul>';
                        require 'widgets/search.php';
                        ?>
                        <!--TODO: Реализовать систему поиска по статьям-->
                        <div class="nav-item me-2 my-auto">
                            <a class="nav-button" href="/profile">
                                Профиль
                            </a>
                        </div>
                        <div class="nav-item my-auto">
                            <a class="nav-button" href="/users/logout">
                                Выйти
                            </a>
                        </div>
                    <?php else: ?>
                        <?php
                        echo '</ul>';
                        require 'widgets/search.php';
                        ?>
                        <div class="nav-item me-2 my-auto">
                            <a class="nav-button" href="/login">
                                Вход
                            </a>
                        </div>
                        <div class="nav-item my-auto">
                            <a class="nav-button" href="/register">
                                Зарегистрироваться
                            </a>
                        </div>
                    <?php endif ?>
            </div>
        </div>
    </nav>
</header>
<div style="max-width: 960px;margin: auto">
    <div class="py-1" style="padding-right:5%;padding-left:5%;background-color:rgba(0,0,0,0);">
        <?= $content ?? '' ?>
    </div>
    <div id="complaintZone"></div>
</div>
<button onclick="goTop()" id="arrowTop" hidden>
    &#8593;
</button>
<!--<footer class="fixed-bottom mt-auto py-3 bg-light">-->
<!--    <span class="text-muted">Place sticky footer content here.</span>-->
<!--</footer>-->
</body>
</html>
