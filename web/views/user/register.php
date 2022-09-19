<div id="bg-img" class="align-items-center vstack justify-content-center">
    <div class="rounded-4 p-4 bg-opacity-75 bg-dark">
        <a class="d-flex vstack mb-3 btn btn-outline-warning" href="/">Вернуться на главную</a>
        <hr style="color: #d0e0dc">
        <p>
            <span class="align-middle text-opacity-50 text-warning">Уже зарегистрированы?</span>
            <a class="btn btn-outline-warning my-1">Войти</a>
        </p>
        <form class="vstack" method="post">
            <div class="vstack">
                <input class="border border-2 rounded-2 bg-black opacity-50 text-warning mb-2 mt-3 placeholder-wave"
                       type="text" name="login" placeholder="Логин" value="<?=$_POST['login'] ?? ''?>">
                <input class="border border-2 rounded-2 bg-black opacity-50 text-warning mb-2 placeholder-wave"
                       type="email" name="email" placeholder="Почта" value="<?=$_POST['email'] ?? ''?>">
                <input class="border border-2 rounded-2 bg-black opacity-50 text-warning mb-2 placeholder-wave"
                       type="password" name="password" placeholder="Пароль" value="<?=$_POST['password'] ?? ''?>">
                <input class="border border-2 rounded-2 bg-black opacity-50 text-warning mb-2 placeholder-wave"
                       type="password" name="retypePassword" placeholder="Повторите пароль">
            </div>
            <button class="mt-5 btn btn-outline-warning">Зарегистрироваться</button>
        </form>
    </div>
</div>


<style>
    #bg-img {
        background-image: url(/images/reg-bg.jpg);
        height: 100vh;
    }
</style>
