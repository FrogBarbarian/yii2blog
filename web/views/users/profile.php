<?php
/** @var \app\models\User $user */
/** @var \app\models\Statistics $statistics */
/** @var bool $isOwn */
/** @var string $tab */

$this->title = $isOwn ? 'Профиль' : 'Пользователь - ' . $user->getLogin();
?>

<div class="rounded-5" style="background-color: #84a2a6;">
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
                    <div class="card-title">
                        <h5 style="text-align: left">
                            <?= $user->getLogin() ?>
                            <span style="font-size: x-small"><?= !$isOwn ? ($user->getIsAdmin() ? '(администратор)' : '(пользователь)') : '' ?></span>
                            <?= (!$isOwn && Yii::$app->session->has('admin') && $user->getIsHidden()) ? '<span class="text-danger" style="font-size: x-small">(профиль скрыт)</span>' : '' ?>
                        </h5>
                        <span style="font-size: small;color:
                        <?php
                        if  ($statistics->getRating() > 0): echo 'green';
                        elseif ($statistics->getRating() < 0): echo 'red';
                        else: echo 'grey';
                        endif;
                        ?>"><?= ($statistics->getRating() > 0 ? '+' : '') . $statistics->getRating() ?></span>
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
            <?php if (!$user->getIsAdmin() && !$isOwn && Yii::$app->session->has('admin')): ?>
                <?php $activeForm = \yii\widgets\ActiveForm::begin(['action' => \yii\helpers\Url::to('/admin/user-settings'), 'options' => ['class' => 'row ms-2']]) ?>
                <input type="hidden" name="id" value="<?= $user->getId() ?>">
                <button type="button" data-bs-toggle="modal" data-bs-target="#adminApply">Сделать админом</button>
                <button type="submit" name="settings" value="comment">Комментарии <?= $user->getCanComment() ? 'разрешены' : 'запрещены' ?></button>
                <button type="submit" name="settings" value="posts">Писать посты <?= $user->getCanWritePosts() ? 'разрешено' : 'запрещено' ?></button>
                <button type="submit" name="settings" value="messages">ЛС <?= $user->getCanWriteMessages() ? 'разрешены' : 'запрещены' ?></button>
                <button type="submit" name="settings" value="ban">Забанить</button>
                <button type="submit" name="settings" value="resetRating">Обнулить рейтинг</button>
                <div class="modal fade" id="adminApply" tabindex="-1" aria-labelledby="adminApplyLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="adminApplyLabel">Вы уверены?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Это назначит пользователя <b><?= $user->getLogin() ?></b> администратором. Отменить возможно через прямой доступ к БД.
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-bs-dismiss="modal">Отмена</button>
                                <button type="submit" name="settings" value="admin">Подтвердить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php \yii\widgets\ActiveForm::end() ?>
            <?php endif ?>
        </div>
    </div>
</div>
