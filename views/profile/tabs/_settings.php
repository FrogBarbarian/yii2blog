<?php

declare(strict_types=1);

/**
 * @var \app\models\User $user
 * @var \yii\web\View $this
 */

$this->title = 'Настройки';
$this->registerJsFile('@js/settings.js');
$this->registerJsFile('@js/utilities/notice.js');
?>
<ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Email - <?= $user->getEmail() ?>
        <button id="createEmailModalButton" class="btn-basic">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Пароль
        <button id="createPasswordModalButton" class="btn-basic">Изменить</button>
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

