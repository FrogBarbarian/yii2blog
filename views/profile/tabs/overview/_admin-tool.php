<?php

declare(strict_types=1);

/**
 * @var \app\models\User $user
 */

$this->registerJsFile('@js/profile-admin.js')
?>
<input type="hidden" id="userId" value="<?= $user->getId() ?>">
<div class="window-basic">
    <p class="p-2">Управление настройками доступа пользователя, будьте аккуратны на данной панели</p>
    <hr>
    <div class="grid-repeat justify-content-around">
        <div class="window-lite justify-content-between">
            <span class="small fw-lighter fst-italic">
                Это обычный пользователь, его можно назначить администратором
            </span>
            <button id="createAdminModalButton" class="btn-basic" type="button">
                Назначить
            </button>
        </div>
        <div class="window-lite justify-content-between">
            <span class="small fw-lighter fst-italic">
                <?php $commentPermissions = $user->getCanComment() ? '' : 'не' ?>
                Этот пользователь
            <span id="commentPermissions" class="text-decoration-underline">
                <?= $commentPermissions ?>
            </span>
            может комментировать посты
            </span>
            <button id="changeCommentPermissionsButton" class="btn-basic">
                Изменить
            </button>
        </div>
        <div class="window-lite justify-content-between">
            <span class="small fw-lighter fst-italic">
            <?php $postPermissions = $user->getCanWritePosts() ? '' : 'не' ?>
            Этот пользователь
            <span id="postPermissions" class="text-decoration-underline">
                <?= $postPermissions ?>
            </span>
            может создавать посты
            </span>
            <button id="changePostPermissionsButton" class="btn-basic">
                Изменить
            </button>
        </div>
        <div class="window-lite justify-content-between">
            <span class="small fw-lighter fst-italic">
            <?php $messagesPermissions = $user->getCanWriteMessages() ? '' : 'не' ?>
            Этот пользователь
            <span id="messagePermissions" class="text-decoration-underline">
                <?= $messagesPermissions ?>
            </span>
            может писать личные сообщения
            </span>
            <button id="changeMessagesPermissionsButton" class="btn-basic">
                Изменить
            </button>
        </div>
        <div class="window-lite justify-content-between">
            <span class="small fw-lighter fst-italic">
                 <?php
                    $text = $user->getIsBanned()
                        ? 'Пользователь <b>забанен</b> и не может использовать свою учетную запись'
                        : 'Пользователь <b>не забанен</b> и имеет доступ к функционалу сайта через свою учетную запись';
                    echo $text;
                 ?>
            </span>
            <button id="banUserButton" class="btn-basic" type="submit" name="settings" value="ban">
                Забанить
            </button>
        </div>
    </div>
</div>
<div class="modal fade" id="adminApply" tabindex="-1" aria-labelledby="adminApplyLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminApplyLabel">Вы уверены?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Это назначит пользователя <b><?= $user->getUsername() ?></b> администратором. Отменить возможно
                через прямой доступ к БД.
            </div>
            <div class="modal-footer">
                <button class="btn" type="button" data-bs-dismiss="modal">Отмена</button>
                <button class="btn" onclick="setUserAdmin()">Подтвердить</button>
            </div>
        </div>
    </div>
</div>
