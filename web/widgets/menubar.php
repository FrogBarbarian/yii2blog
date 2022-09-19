<div class="row d-flex fixed-top">
    <nav class="navbar navbar-expand-sm bg-dark">
        <button class="ms-1 navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="btn btn-outline-warning rounded">&udhar;</span>
        </button>
        <div class="collapse navbar-collapse me-auto" id="navbarSupportedContent">
        <?php if (isset(Yii::$app->session['login'])): ?>
            <button>Мои счета</button>
            <button>Добавить сделку</button>
            <button>Профиль</button>
            <button>Выйти</button>
        <?php else: ?>
            <spacer class="me-auto"></spacer>
            <ul class="navbar-nav vstack btn-group justify-content-end">
                <li><a class="d-flex ms-3 btn btn-outline-warning mt-1" style="max-width: 120px" href="/register">Регистрация</a></li>
                <li><a class="d-flex ms-3 btn btn-outline-warning me-3 mt-1" style="max-width: 120px" id="login">Вход</a></li>
            </ul>
        <?php endif ?>
        </div>
    </nav>
    <div class="hstack bg-light">
        <div class="bg-white mx-1 d-none d-sm-block">
            <a class=" nav-link" href="/"><b>ГЛАВНАЯ СТРАНИЦА</b></a>
        </div>
        <div class="vr"></div>
        <div class="mx-1">
            Бегущая строка с активами (если залогинен, то можно выбирать список активом)
        </div>
    </div>
    <hr>
</div>







