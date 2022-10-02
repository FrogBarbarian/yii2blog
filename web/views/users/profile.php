<?php
/** @var \app\models\User $user */
/** @var bool $isOwn */
/** @var string $tab */

$this->title = $isOwn ? 'Профиль' : 'Пользователь - ' . $user->getLogin();
?>

<div class="rounded-5" style="background-color: #84a2a6;">
    <div class="mx-3 py-5"> <div class="col">
            <?php if ($isOwn): ?>
                <div class="col-2">
                    <div class="list-group list-group-horizontal mb-1" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="list" href="#list-home" role="tab" aria-controls="list-home">Профиль</a>
                        <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="list" href="#list-messages" role="tab" aria-controls="list-messages">Сообщения</a>
                        <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="list" href="#list-settings" role="tab" aria-controls="list-settings">Настройки</a>
                    </div>
                </div>
            <?php endif ?>
            <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
                <div class="card-body">
                    <div class="card-title">
                        <h5 style="text-align: left">
                            <?= $user->getLogin()?>
                            <?=(!$isOwn && Yii::$app->session->has('admin') && $user->getIsHidden()) ? '<span class="text-danger" style="font-size: x-small">(профиль скрыт)</span>' : ''?>
                        </h5>
                    </div>
            <div class="col">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list"><?php require 'profile-tabs/overview.php'?></div>
                    <?php if ($isOwn): ?>
                    <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list"><?php require 'profile-tabs/pm.php'?></div>
                    <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list"><?php require 'profile-tabs/settings.php'?></div>
                    <?php endif ?>
                </div>
            </div>
                </div>
            </div>
        </div>
    </div>
</div>
