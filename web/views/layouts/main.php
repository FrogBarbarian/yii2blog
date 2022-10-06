<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=$this->title ?? 'Need to setup name'?></title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css" />
    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/jquery.js"></script>
    <script src="../../assets/js/random-post.js"></script>
</head>
<body style="background-image: url('../../assets/images/favicon.svg');background-color: #1a384d;">
<header class="sticky-top">
    <nav class="navbar navbar-expand-lg" style="background-color: rgba(101,101,96,0.95)">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="../../assets/images/favicon.svg" alt="Logo" width="30" height="24"
                     class="d-inline-block align-text-top">
                Блог
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <button type="button" id="randomPost" class="nav-link" style="color: #d0e0dc;background-color: rgba(0,0,0,0)">
                            Случайная статья
                        </button>
                    </li>
                    <?php if (isset(Yii::$app->session['login'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/new-post" style="color: #d0e0dc">Создать пост</a>
                        </li>
                            <?php if (isset(Yii::$app->session['admin'])): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= ADMIN_PANEL ?>" style="color: #d0e0dc">Админ-панель</a>
                                </li>
                            <?php
                            endif;
                            echo '</ul>';
                            require 'widgets/search.php';
                            ?>
                <!--TODO: Реализовать систему поиска по статьям-->
                <div class="nav-item me-2">
                    <a class="nav-link" href="/profile">
                        Профиль
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="/users/logout">
                        Выйти
                    </a>
                </div>
                <?php else: ?>
                    <?php
                    echo '</ul>';
                    require 'widgets/search.php';
                    ?>
                    <div class="nav-item me-2">
                        <a class="nav-link" href="/login">
                            Вход
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" href="/register">
                            Зарегистрироваться
                        </a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </nav>
</header>
<div style="
        min-height: 90vh;
        max-width: 960px;
        margin: auto;
        padding-top: 1%;
    ">
    <?= $content ?? '' ?>
</div>
<!--<footer class="fixed-bottom mt-auto py-3 bg-light">-->
<!--    <span class="text-muted">Place sticky footer content here.</span>-->
<!--</footer>-->
</body>
</html>
