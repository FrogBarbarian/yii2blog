<?php

/**
 * @var \app\models\Post $post
 * @var \app\models\User $owner
 * @var \app\models\User $user
 * @var \app\models\Comment[] $comments
 * @var bool $visitorIsLogin
 */

use src\helpers\ConstructHtml;

$this->title = $post->getTitle();
$postIsCommentable = $post->getIsCommentable();

if ($visitorIsLogin) {
    $userIsAdmin = $user->getIsAdmin();
    $userCanComment = $user->getCanComment();
} else {
    $userIsAdmin = false;
    $userCanComment = false;
}
?>
<script src="../../assets/js/post-rating.js"></script>
<script src="../../assets/js/comments.js"></script>
<input type="hidden" id="postId" value="<?= $post->getId() ?>">
<div class="btn-group mb-1">
    <?php if ($user !== null): ?>
        <input type="hidden" id="userId" value="<?= $user->getId() ?>">
        <?php if ($owner->getId() === $user->getId()): ?>
            <a class="list-group-item list-group-item-action" href="/edit-post?id=<?= $post->getId() ?>"
               data-bs-toggle="tooltip" data-bs-placement="top" title="Отредактировать" style="width: auto">
                <img src="../../assets/images/post-edit.svg" alt="edit" width="30" height="30"
                     class="d-inline-block">
            </a>
            <button class="list-group-item list-group-item-action" type="button"
                    data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                    title="Удалить пост" style="width: auto">
                <img src="../../assets/images/post-delete.svg"
                     alt="delete" width="30" height="30"
                     class="d-inline-block">
            </button>
            <?php require 'widgets/delete-post.php' ?>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($visitorIsLogin && $userIsAdmin): ?>
        <button type="button" id="commentsButton" class="btn btn-light">
            <img src="../../assets/images/<?= $postIsCommentable ? 'comment-enabled' : 'comment-disabled' ?>.svg"
                 alt="comments" width="24" height="24">
        </button>
    <?php endif; ?>
</div>
    <div class="mx-3 py-5">
        <?php if (Yii::$app->session->hasFlash('postAlreadyUpdated')): ?>
            <div class="alert alert-warning rounded-4" role="alert">
                <?= Yii::$app->session->getFlash('postAlreadyUpdated') ?>
            </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?= $post->getTitle() ?></h5>
                <p class="card-text"><?= $post->getBody() ?></p>
            </div>
            <div class="card-footer">
                <div>
                    Опубликован: <b><?= $post->getDate() ?></b>.
                    Просмотров: <?= $post->getViews() ?>.
                    <a href="/user?id=<?= $owner->getId() ?>">
                        Автор - <?= $post->getAuthor() ?>
                    </a>
                </div>
                <hr>
                <!--TODO: по тегу можно перейти в поиск по тегу-->
                <?php foreach ($post->getTagsArray($post->getTags()) as $tag): ?>
                    <?= $tag ?>
                <?php endforeach ?>
            </div>
        </div>
        <div class="mb-2">
            <span id="rating-container"><?= ConstructHtml::rating($post->getRating()) ?></span>
            <?php if ($visitorIsLogin && $post->getAuthor() !== $user->getLogin()): ?>
                <p>
                    <button type="button" id="like" style="background-color: <?= $post->isUserLikeIt($user->getId()) ? 'green' : 'grey'?>">like</button>
                    <button type="button" id="dislike" style="background-color: <?= $post->isUserDislikeIt($user->getId()) ? 'red' : 'grey'?>">dislike</button>
                </p>
            <?php endif ?>
        </div>
        <!--Начало комментариев-->
        <div id="comments-permissions">
            <?php if (!$postIsCommentable): ?>
                <div class="alert alert-secondary text-center text-danger" role="alert">
                    Комментарии запрещены.
                </div>
            <?php endif ?>
        </div>
        <h5 id="commentsAmount" style="padding-left: 5%"><?= ConstructHtml::commentsAmount(count($comments)) ?></h5>
        <div id="#commentForm">
            <?php if ($visitorIsLogin && $userCanComment && $postIsCommentable) require 'widgets/comment-field.php' ?>
        </div>
        <ul class="list-group" id="comments" style="padding-left: 5%;padding-right: 5%">
            <?= $comments ? ConstructHtml::comments($comments) : '' ?>
        </ul>
        <?php if ($visitorIsLogin && $postIsCommentable): ?>
            <?php if (!$userCanComment): ?>
                <div class="alert alert-danger text-center" role="alert">
                    Вам запрещено комментировать.
                </div>
            <?php endif ?>
        <?php endif ?>
</div>
