<?php

declare(strict_types=1);

/** @var \app\models\User $user */
?>

<ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Имя пользователя - <?= $user->getUsername() ?>
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Email - <?= $user->getEmail() ?>
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Пароль
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Публичный профиль
        <div>
            <div class="form-check form-switch">
                <input onchange="changeVisibility(this)" class="form-check-input" <?= $user->getIsHidden() ? '' : 'checked' ?> type="checkbox"
                       id="profileVisibility">
                <label class="form-check-label" for="profileVisibility"></label>
            </div>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Личные сообщения
        <div class="form-check form-switch">
            <input class="form-check-input" checked type="checkbox" id="allowPM">
            <label class="form-check-label" for="allowPM"></label>
        </div>
    </li>
</ul>
<div id="settingsModal"></div>
<script src="../../web/assets/js/settings.js"></script>
