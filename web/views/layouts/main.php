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
</head>
<body style="background-color: rgba(77,101,27,0.58);">
<div>
    <header class="fixed-top">
        <nav class="navbar navbar-expand-lg" style="background-color: #39828f">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="../../assets/images/favicon.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                    Блог
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="/random" style="color: #a4001b">Случайная статья</a>
                        </li>
                        <?php if (isset(Yii::$app->session['login'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/new-post" style="color: #a4001b">Создать пост</a>
                            </li>
                        <?php endif ?>
                    </ul>
                    <!--TODO: Реализовать систему поиска по статьям-->
                    <?php if (isset(Yii::$app->session['login'])): ?>
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
    <div style="margin-top: 60px;">
        <div>
            <?=$content ?? ''?>
        </div>

    </div>
</div>
</body>
</html>
