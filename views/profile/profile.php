<?php

declare(strict_types=1);

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
 * @var \yii\web\View $this
 */

use src\helpers\ConstructHtml;

$this->title = $isOwn ? 'Профиль' : $user->getUsername();

 if ($visitor !== null && !$user->getIsAdmin() && !$isOwn && $visitor->getIsAdmin()) {
    echo $this->render('tabs/overview/_admin-tool', ['user' => $user]);
}

 $this->registerJsFile('@js/utilities/message-modal.js');
?>
<div class="mx-3 pt-5 pb-2">
    <div class="col">
        <?php if ($isOwn): ?>
            <div class="col-2">
                <div class="list-group list-group-horizontal mb-1">
                    <a class="btn-basic a-btn <?= ($tab !== 'mailbox' && $tab !== 'settings') ? 'a-btn-active' : '' ?>"
                       href="/profile">
                        Профиль
                    </a>
                    <a class="btn-basic a-btn <?= $tab === 'mailbox' ? 'a-btn-active' : '' ?>"
                       href="?tab=mailbox">Сообщения</a>
                    <a class="btn-basic a-btn <?= $tab === 'settings' ? 'a-btn-active' : '' ?>"
                       href="?tab=settings">Настройки</a>
                </div>
            </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto window-basic">
            <div class="card-body">
                <div class="hstack card-title">
                    <div class="col">
                        <h5 class="text-start">
                            <?= $user->getUsername() ?>
                            <span class="x-small"><?= !$isOwn ? ($user->getIsAdmin() ? '(администратор)' : '(пользователь)') : '' ?></span>
                            <?= (!$isOwn && $visitor !== null && $visitor->getIsAdmin() && $user->getIsHidden()) ? '<span class="text-danger x-small">(профиль скрыт)</span>' : '' ?>
                        </h5>
                        <?= ConstructHtml::rating($statistics->getRating()) ?>
                    </div>
                    <?php if (!$isOwn && $visitor !== null): ?>
                        <button onclick="createMessageModal('<?= $user->getUsername() ?>')"
                                class="btn-basic">
                            <img src="<?= IMAGES ?>mailbox/button-new-message.svg" width="24" alt="send message"/>
                        </button>
                        <?php if (!$visitor->getIsAdmin()): ?>
                            <button onclick="createComplaint('user', '<?= $user->getId() ?>')"
                                    class="btn-basic">
                                <img src="<?= IMAGES ?>other-buttons/create-complaint.svg" width="24" alt="create complaint"/>
                            </button>
                        <?php endif ?>
                    <?php endif ?>
                </div>
                <?= match ($tab) {
                    'mailbox' => $this->render('tabs/_mailbox', ['messages' => $messages]),
                    'settings' => $this->render('tabs/_settings', ['user' => $user]),
                    default => $this->render('tabs/_overview', [
                        'user' => $user,
                        'visitor' => $visitor,
                        'statistics' => $statistics,
                        'posts' => $posts,
                        'tmpPosts' => $tmpPosts,
                        'complaints' => $complaints,
                        'isOwn' => $isOwn,
                    ]),
                } ?>
            </div>
        </div>
    </div>
</div>
