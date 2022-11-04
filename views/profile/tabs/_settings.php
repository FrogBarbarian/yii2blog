<?php

declare(strict_types=1);

/**
 * @var \app\models\User $user
 * @var \yii\web\View $this
 */

$this->title = 'Настройки';
$this->registerJsFile('@web/assets/js/settings.js');
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
        <button id="createPasswordModal" class="btn-basic">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Публичный профиль
        <div>
            <label class="switch">
                <input
                    <?= $user->getIsHidden() ? '' : 'checked' ?>
                        type="checkbox"
                        id="profileVisibility"
                ><span class="slider"></span>
            </label>
        </div>

    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Личные сообщения
        <label class="switch">
            <input
                <?= !$user->getIsMessagesOpen() ? '' : 'checked' ?>
                    type="checkbox"
                    id="messagesStatus"
            ><span class="slider"></span>
        </label>
    </li>
</ul>
