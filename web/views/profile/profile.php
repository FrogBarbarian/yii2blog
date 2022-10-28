<?php
/**
 * @var \app\models\User $user
 * @var \app\models\User $visitor
 * @var \app\models\Statistic $statistics
 * @var bool $isOwn
 * @var string $tab
 * @var \app\models\Post[] $posts
 * @var \app\models\TmpPost[] $tmpPosts
 * @var \app\models\Complaint[] $complaints
 * @var \app\models\Message[] $messages
 */

use app\components\ProfileMailboxWidget;
use app\components\ProfileOverviewWidget;
use app\components\ProfileSettingsWidget;
use src\helpers\ConstructHtml;

$this->title = $isOwn ? 'Профиль' : $user->getUsername();
?>


<div class="mx-3 pt-5 pb-2">
    <div class="col">
        <?php if ($isOwn): ?>
            <div class="col-2">
                <div class="list-group list-group-horizontal mb-1">
                    <a class="btn-basic a-btn <?= ($tab !== 'mailbox' && $tab !== 'settings') ? 'a-btn-active' : '' ?>" href="/profile">
                        Профиль
                    </a>
                    <a class="btn-basic a-btn <?= $tab === 'mailbox' ? 'a-btn-active' : '' ?>"
                       href="?tab=mailbox">Сообщения</a>
                    <a class="btn-basic a-btn <?= $tab === 'settings' ? 'a-btn-active' : '' ?>"
                       href="?tab=settings">Настройки</a>
                </div>
            </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <div class="hstack card-title">
                    <div class="col">
                        <h5 style="text-align: left">
                            <?= $user->getUsername() ?>
                            <span style="font-size: x-small"><?= !$isOwn ? ($user->getIsAdmin() ? '(администратор)' : '(пользователь)') : '' ?></span>
                            <?= (!$isOwn && $visitor !== null && $visitor->getIsAdmin() && $user->getIsHidden()) ? '<span class="text-danger" style="font-size: x-small">(профиль скрыт)</span>' : '' ?>
                        </h5>
                        <?= ConstructHtml::rating($statistics->getRating()) ?>
                    </div>
                    <?php if ($visitor !== null && !$visitor->getIsAdmin() && !$isOwn): ?>
                        <button type="button" style="max-width: 48px"
                                onclick="createComplaint('user', <?= $user->getId() ?>, <?= $visitor->getId() ?>)"
                                class="btn btn-light col">
                            <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
                        </button>
                    <?php endif ?>
                </div>
                <?php
                switch ($tab) {
                    case 'mailbox':
                        echo ProfileMailboxWidget::widget(['messages' => $messages]);
                        break;
                    case 'settings':
                        echo ProfileSettingsWidget::widget(['user' => $user]);
                        break;
                    default:
                        echo ProfileOverviewWidget::widget([
                            'user' => $user,
                            'visitor' => $visitor,
                            'statistics' => $statistics,
                            'posts' => $posts,
                            'tmpPosts' => $tmpPosts,
                            'complaints' => $complaints,
                            'isOwn' => $isOwn,
                        ]);
                        break;
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php if ($visitor !== null && !$user->getIsAdmin() && !$isOwn && $visitor->getIsAdmin()): ?>
    <script src="../../assets/js/profile-admin.js"></script>
    <input type="hidden" id="userId" value="<?= $user->getId() ?>">
    <div class="admin-user-control">
        <p style="text-align: center">Манипуляции с пользователем</p>
        <button type="button" data-bs-toggle="modal" data-bs-target="#adminApply">
            Назначить админом
        </button>
        <button onclick="setCommentsPermissions(this)">
            Комментарии <?= $user->getCanComment() ? 'разрешены' : 'запрещены' ?>
        </button>
        <button onclick="setCreatePostsPermissions(this)">Писать
            посты <?= $user->getCanWritePosts() ? 'разрешено' : 'запрещено' ?>
        </button>
        <button onclick="setPrivateMessagesPermissions(this)">
            ЛС <?= $user->getCanWriteMessages() ? 'разрешены' : 'запрещены' ?>
        </button>
        <button type="submit" name="settings" value="ban">
            Забанить
        </button> <!--TODO: remark-->
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
<?php endif ?>
