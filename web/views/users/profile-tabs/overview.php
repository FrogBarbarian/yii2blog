<?php
/**
 * @var bool $isOwn
 * @var \app\models\User $user
 * @var \app\models\User $visitor
 * @var \app\models\Statistic $statistics
 * @var \app\models\Post[] $posts
 * @var \app\models\TmpPost[] $postsTmp
 * @var \app\models\Complaint[] $complaints
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
?>

<?php if ($isOwn): ?>
    <div class="card-text">
        Профиль <?= $user->getIsHidden() ? 'скрытый' : 'публичный' ?>
    </div>
<?php endif ?>

<?php if ($user->getIsHidden() && !$isOwn && !$visitor->getIsAdmin()): ?>
    <p class="text-danger">Профиль скрыт</p>
<?php else: ?>
    <hr>
    <h4 class="text-center"><?= $isOwn ? 'Статистика' : 'Статистика пользователя' ?></h4>
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Написано постов
            <span class="badge bg-primary rounded-pill"><?= $statistics->getPosts() ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Просмотров
            <span class="badge bg-primary rounded-pill"><?= $statistics->getViews() ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Комментариев
            <span class="badge bg-primary rounded-pill"><?= $statistics->getComments() ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Лайков
            <span class="badge bg-success rounded-pill"><?= $statistics->getLikes() ?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Дизлайков
            <span class="badge bg-danger rounded-pill"><?= $statistics->getDislikes() ?></span>
        </li>
    </ul>
    <hr>
    <?php if ($posts): ?>
        <h4 class="text-center">
            <a class="post-link" href="/author/<?= $user->getUsername() ?>" target="_blank">
                Опубликованные посты
            </a>
        </h4>
        <!--TODO: Добавить пагинацию-->
    <div class="list-group">
        <?php foreach ($posts as $post): ?>
            <a href="/post?id=<?= $post->getId() ?>" class="list-group-item list-group-item-action"
               aria-current="true">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?= $post->getPreview($post->getTitle(), 50, '') ?></h5>

                </div>
                <p class="mb-1"><?= $post->getPreview($post->getBody(), 150, '') ?></p>
                <small class="hstack">
                        <span style="font-style: italic">
                        <?= NormalizeData::passedTime($post->getDatetime()) ?>
                        </span>
                    <div class="col text-muted" style="font-size:small;text-align:end;">
                        <?= ConstructHtml::rating($post->getRating()) ?>
                        <span>
                                <?= $post->getViews() ?>
                                <img src="/assets/images/views.svg" width="16" alt="views">
                            </span>
                        <span>
                                <?= $post->getCommentsAmount() ?>
                                <img src="/assets/images/comments.svg" width="16" alt="comments"/>
                            </span>
                    </div>
                </small>
            </a>
        <?php endforeach ?>
    </div>
    <?php else: ?>
    <?= $isOwn ? 'Вы еще не опубликовали ни одного поста.' : "{$user->getUsername()} еще не написал ни одного поста." ?>
<?php endif ?>
<?php endif ?>

<?php if ($isOwn): ?>
    <?php if ($postsTmp && !$user->getIsAdmin()): ?>
<hr>
    <h5 class="text-center">Посты, ожидающие проверки администрацией.</h5>
<div class="list-group">
    <?php foreach ($postsTmp as $postTmp): ?>
    <!--TODO: Ссылка на tmp пост-->
    <a href="#" class="list-group-item list-group-item-action">
        <?= $postTmp->getTitle() ?>
            | <?= $postTmp->getIsNew() ? 'Новый' : 'Отредактированный' ?>
    </a>
    <?php endforeach ?>
</div>
<?php endif ?>
    <?php if ($complaints && !$user->getIsAdmin()): ?>
<hr>
    <h5 class="text-center">Отправленные жалобы.</h5>
<div class="accordion accordion-flush" id="complaints">
    <?php foreach ($complaints as $complaint): ?>
                <?php $object = match ($complaint->getObject()) {
    'post' => 'пост',
    'comment' => 'комментарий',
    'user' => 'пользователя',
    default => 'ошибка типа объекта',
    } ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-heading<?= $complaint->getId() ?>">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#flush-collapse<?= $complaint->getId() ?>" aria-expanded="false"
                    aria-controls="flush-collapse<?= $complaint->getId() ?>">
                        <span class="col text-start">
                                 Жалоба на <?= $object ?>
                            </span>
                <span class="col text-end me-2 small fst-italic">
                                <?= NormalizeData::passedTime($complaint->getDatetime()) ?>
                            </span>
            </button>
        </h2>
        <div id="flush-collapse<?= $complaint->getId() ?>" class="accordion-collapse collapse"
             aria-labelledby="flush-heading<?= $complaint->getId() ?>" data-bs-parent="#complaints">
            <div class="accordion-body">
                <?= $complaint->getComplaint() ?>
                <br>
                <a class="complaint-link"
                   href="/<?= "{$complaint->getObject()}?id={$complaint->getObjectId()}" ?>"
                   target="_blank">
                    Ссылка на <?= $object ?>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>
    <?php if ($user->getIsAdmin()): ?>
<hr>
    <h5 class="text-center">Админский функционал</h5>
<ul class="list-group mb-1">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= $postsTmp ? 'Посты для проверки' : 'Постов для проверки нет' ?>
        <span class="badge bg-primary rounded-pill"><?= $postsTmp ? count($postsTmp) : '' ?></span>
    </li>
    <!--TODO: Добавить функционал жалоб-->
    <li class="list-group-item d-flex justify-content-between align-items-center">
        <?= $complaints ? 'Жалобы пользователей' : 'Жалоб пользователей нет' ?>
        <span class="badge bg-primary rounded-pill"><?= $complaints ? count($complaints) : '' ?></span>
    </li>
</ul>
<a href="<?= ADMIN_PANEL ?>" class="btn btn-outline-dark">Админ-панель</a>
<br>
<?php endif ?>
<?php endif ?>
