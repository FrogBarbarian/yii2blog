<?php
/**
 * @var \app\models\User $user
 * @var \app\models\Statistics $statistics
 * @var bool $isOwn
 * @var string $tab
 * @var \yii\web\Session $session
 */

$this->title = $isOwn ? 'Профиль' : 'Пользователь - ' . $user->getLogin();
?>


<div class="mx-3 py-5">
    <div class="col">
        <?php if ($isOwn): ?>
            <div class="col-2">
                <div class="list-group list-group-horizontal mb-1">
                    <a class="list-group-item list-group-item-action <?= ($tab !== 'pm' && $tab !== 'settings') ? 'active' : '' ?>"
                       id="list-home-list" href="?tab=overview">Профиль</a>
                    <a class="list-group-item list-group-item-action <?= $tab === 'pm' ? 'active' : '' ?>"
                       id="list-messages-list" href="?tab=pm">Сообщения</a>
                    <a class="list-group-item list-group-item-action <?= $tab === 'settings' ? 'active' : '' ?>"
                       id="list-settings-list" href="?tab=settings">Настройки</a>
                </div>
            </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <div class="hstack card-title">
                    <div class="col">
                        <h5 style="text-align: left">
                            <?= $user->getLogin() ?>
                            <span style="font-size: x-small"><?= !$isOwn ? ($user->getIsAdmin() ? '(администратор)' : '(пользователь)') : '' ?></span>
                            <?= (!$isOwn && $session->has('admin') && $user->getIsHidden()) ? '<span class="text-danger" style="font-size: x-small">(профиль скрыт)</span>' : '' ?>
                        </h5>
                        <span style="font-size: small;color:
                        <?php
                        if ($statistics->getRating() > 0): echo 'green';
                        elseif ($statistics->getRating() < 0): echo 'red';
                        else: echo 'grey';
                        endif;
                        ?>"><?= ($statistics->getRating() > 0 ? '+' : '') . $statistics->getRating() ?></span>
                    </div>
                    <?php if ($session->has('login') && !$session->has('admin') && !$isOwn): ?>
                        <button type="button" style="max-width: 48px"
                                onclick="createComplaint('user', <?= $user->getId() ?>, <?= $session['id'] ?>)"
                                class="btn btn-light col">
                            <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
                        </button>
                    <?php endif ?>
                </div>
                <?php
                try {
                    require "profile-tabs/{$tab}.php";
                } catch (Exception) {
                    require "profile-tabs/overview.php";
                }
                ?>
            </div>
        </div>
        <?php if (!$user->getIsAdmin() && !$isOwn && $session->has('admin')): ?>
            <script src="../../assets/js/profile-admin.js"></script>
            <input type="hidden" id="userId" value="<?= $user->getId() ?>">
            <button type="button" data-bs-toggle="modal" data-bs-target="#adminApply">Сделать админом</button>
            <button onclick="setCommentsPermissions(this)">
                Комментарии <?= $user->getCanComment() ? 'разрешены' : 'запрещены' ?></button>
            <button onclick="setCreatePostsPermissions(this)">Писать
                посты <?= $user->getCanWritePosts() ? 'разрешено' : 'запрещено' ?></button>
            <button onclick="setPrivateMessagesPermissions(this)">
                ЛС <?= $user->getCanWriteMessages() ? 'разрешены' : 'запрещены' ?></button>
            <button type="submit" name="settings" value="ban">Забанить</button> <!--TODO: remark-->
            <div class="modal fade" id="adminApply" tabindex="-1" aria-labelledby="adminApplyLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="adminApplyLabel">Вы уверены?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Это назначит пользователя <b><?= $user->getLogin() ?></b> администратором. Отменить возможно
                            через прямой доступ к БД.
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-bs-dismiss="modal">Отмена</button>
                            <button onclick="setUserAdmin()">Подтвердить</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>