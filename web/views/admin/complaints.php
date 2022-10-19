<?php
/**
 * @var int $amountComplaints
 * @var \app\models\Complaint[] $usersComplaints
 * @var \app\models\Complaint[] $postsComplaints
 * @var \app\models\Complaint[] $commentsComplaints
 */

declare(strict_types = 1);

use app\components\AdminMenuWidget;
use src\helpers\NormalizeData;

echo AdminMenuWidget::widget(['amountComplaints' => $amountComplaints]);
?>
<script src="../../assets/js/admin/complaints.js"></script>
<span class="admin-panel-header">Жалобы пользователей</span>
<hr style="color: #14376c">
<h6>
   На этой странице представлены жалобы пользователей на какой либо объект сайта.
</h6>
<hr>
<?php if ($amountComplaints > 0): ?>
    <p class="admin-panel-info">
        Текущие жалобы разбиты по категориям и отображены от старых к новым.
    </p>
<div class="accordion my-3">
    <?php if ($usersComplaints !== []): ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="complaintsUsers">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#complaintsUsersAria" aria-expanded="false" aria-controls="complaintsUsersAria">
                Жалобы на пользователей
                <span class="badge bg-danger mx-1">
                    <?= count($usersComplaints) ?>
                </span>
            </button>
        </h2>
        <div id="complaintsUsersAria" class="accordion-collapse collapse" aria-labelledby="complaintsUsers">
            <div class="accordion-body">
                <?php foreach ($usersComplaints as $complaint): ?>
                <div id="complaint_<?= $complaint->getId() ?>">
                    <p style="font-size: smaller">
                        <a class="author-link" href="/user?id=<?= $complaint->getSenderId() ?>" target="_blank">
                            <?= $complaint->getSenderUsername() ?>
                        </a>
                        <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
                        отправил жалобу на
                        <a class="author-link" href="/<?= "{$complaint->getObject()}?id={$complaint->getObjectId()}" ?>" target="_blank">
                            пользователя
                        </a>
                    </p>
                    <span style="font-size: x-small;font-style: italic;color: grey;display: block">
                    Содержание жалобы:
                </span>
                    <?= $complaint->getComplaint() ?>
                    <button onclick="closeComplaint('<?= $complaint->getId() ?>')" style="display: block;">Закрыть жалобу</button>
                    <hr style="margin: .5rem 0 1rem 0">
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php
    endif;
    if ($commentsComplaints !== []):
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="complaintsComments">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#complaintsCommentsAria" aria-expanded="false" aria-controls="complaintsCommentsAria">
                Жалобы на комментарии
                <span class="badge bg-danger mx-1">
                    <?= count($commentsComplaints) ?>
                </span>
            </button>
        </h2>
        <div id="complaintsCommentsAria" class="accordion-collapse collapse" aria-labelledby="complaintsComments">
            <div class="accordion-body">
                <?php foreach ($commentsComplaints as $complaint): ?>
                <div id="complaint_<?= $complaint->getId() ?>">
                    <p style="font-size: smaller">
                        <a class="author-link" href="/user?id=<?= $complaint->getSenderId() ?>" target="_blank">
                            <?= $complaint->getSenderUsername() ?>
                        </a>
                        <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
                        отправил жалобу на
                        <a class="author-link" href="/<?= "{$complaint->getObject()}?id={$complaint->getObjectId()}" ?>" target="_blank">
                            комментарий
                        </a>
                    </p>
                    <span style="font-size: x-small;font-style: italic;color: grey;display: block">
                    Содержание жалобы:
                </span>
                    <?= $complaint->getComplaint() ?>
                    <button onclick="closeComplaint('<?= $complaint->getId() ?>')" style="display: block;">Закрыть жалобу</button>
                    <hr style="margin: .5rem 0 1rem 0">
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php
    endif;
    if ($postsComplaints !== []):
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="complaintsPosts">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#complaintsPostsAria" aria-expanded="false" aria-controls="complaintsPostsAria">
                Жалобы на посты
                <span class="badge bg-danger mx-1">
                    <?= count($postsComplaints) ?>
                </span>
            </button>
        </h2>
        <div id="complaintsPostsAria" class="accordion-collapse collapse" aria-labelledby="complaintsPosts">
            <div class="accordion-body">
                <?php foreach ($postsComplaints as $complaint): ?>
                <div id="complaint_<?= $complaint->getId() ?>">
                    <p style="font-size: smaller">
                        <a class="author-link" href="/user?id=<?= $complaint->getSenderId() ?>" target="_blank">
                            <?= $complaint->getSenderUsername() ?>
                        </a>
                        <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
                        отправил жалобу на
                        <a class="author-link" href="/<?= "{$complaint->getObject()}?id={$complaint->getObjectId()}" ?>" target="_blank">
                            пост
                        </a>
                    </p>
                    <span style="font-size: x-small;font-style: italic;color: grey;display: block">
                    Содержание жалобы:
                </span>
                    <?= $complaint->getComplaint() ?>
                    <button onclick="closeComplaint('<?= $complaint->getId() ?>')" style="display: block;">Закрыть жалобу</button>
                    <hr style="margin: .5rem 0 1rem 0">
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php endif ?>
</div>
<?php else: ?>
    <p class="admin-panel-info">
        На данный момент жалоб нет.
    </p>
<?php endif ?>
