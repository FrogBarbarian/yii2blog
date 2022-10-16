<?php
/** @var \app\models\User[] $users */

?>
<h5 class="card-title">Обзор</h5>
<h6>Список пользователей:</h6>
<?php foreach ($users as $user): ?>
    <!--TODO: Открытие профиля пользователя и там изменение прав-->
    <?php if ($user->getUsername() === Yii::$app->session['login'] || $user->getUsername() === '_guest') continue ?>
    <?=$user->getUsername()?> | <?=$user->getEmail()?> | <?=$user->getIsAdmin() ? 'Администратор' : 'Пользователь'?>

    <a class="btn btn-outline-dark" href="/user?id=<?= $user->getId()?>">
        Перейти в профиль
    </a>
    <br>
<?php endforeach ?>