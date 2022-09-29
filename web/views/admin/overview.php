<?php
/** @var \app\models\User[] $users */

use yii\widgets\ActiveForm;
?>
<h5 class="card-title">Обзор</h5>
<h6>Список пользователей:</h6>
<?php foreach ($users as $user): ?>
    <!--TODO: Открытие профиля пользователя и там изменение прав-->
    <?php if ($user->getLogin() === Yii::$app->session['login'] || $user->getLogin() === '_guest') continue ?>
    <?=$user->getLogin()?> | <?=$user->getEmail()?> | <?=$user->getIsAdmin() ? 'Администратор' : 'Пользователь'?>
    <?php $activeForm = ActiveForm::begin(['id' => 'change-admin-form'])?>
    <button class="btn btn-outline-dark" type="submit" name="changeStatus">
        <?=$user->getIsAdmin() ? 'Сделать пользователем' : 'Сделать администратором' ?>
    </button>
    <input type="hidden" name="id" value="<?=$user->getId()?>">
    <input type="hidden" name="isAdmin" value="<?=$user->getIsAdmin()?>">
    <?php $activeForm = ActiveForm::end()?>
    <br>
<?php endforeach ?>
