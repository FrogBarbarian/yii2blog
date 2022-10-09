<?php

/**
 * @var \app\models\Post $post
 * @var \app\models\User $owner
 * @var \app\models\User $user
 * @var \app\models\Comment[] $comments
 * @var bool $visitorIsLogin
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;

$this->title = $post->getTitle();
$postIsCommentable = $post->getIsCommentable();

if ($visitorIsLogin) {
    $userIsAdmin = $user->getIsAdmin();
    $userCanComment = $user->getCanComment();
    $userIsAuthor = $owner->getId() === $user->getId();
} else {
    $userIsAdmin = false;
    $userCanComment = false;
    $userIsAuthor = false;
}
?>
<script src="../../assets/js/post.js"></script>
<script src="../../assets/js/comments.js"></script>
<input type="hidden" id="postId" value="<?= $post->getId() ?>">
<div class="mx-3 py-2">
    <?php if (Yii::$app->session->hasFlash('postAlreadyUpdated')): ?>
        <div class="alert alert-warning rounded-4" role="alert">
            <?= Yii::$app->session->getFlash('postAlreadyUpdated') ?>
        </div>
    <?php endif ?>
    <div class="card mx-auto rounded-0">
        <div class="card-body">
            <h5 class="card-title"><?= $post->getTitle() ?></h5>
            <p class="card-text"><?= $post->getBody() ?></p>
        </div>
        <div class="card-footer">
            <div class="hstack">
                <div class="col text-start" style="font-size: small">
                    <?= $post->getViews() . ' ' . NormalizeData::wordForm($post->getViews(), 'просмотров', 'просмотр', 'просмотра') ?>
                </div>
                <div class="col text-end" style="font-size: small">
                    <?= NormalizeData::date($post->getDate()) ?>
                    &nbsp;
                <a href="/user?id=<?= $owner->getId() ?>" style="color: dodgerblue;text-decoration:none">
                    <?= $post->getAuthor() ?>
                </a>
                </div>
            </div>
            <!--TODO: по тегу можно перейти в поиск по тегу. Сделать теги-->
            <?php foreach ($post->getTagsArray($post->getTags()) as $tag): ?>
                <?= $tag ?>
            <?php endforeach ?>
            <div class="hstack">
                <div class="col" id="rating-container">
                    <?= ConstructHtml::rating($post->getRating()) ?>
                </div>
                <?php if ($visitorIsLogin): ?>
                    <div class="col text-end">
                        <!--Кнопки управления-->
                        <div class="btn-group">
                            <?php if ($userIsAuthor): ?>
                                <a class="btn btn-light"
                                   href="/edit-post?id=<?= $post->getId() ?>"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Отредактировать"
                                   style="width: auto">
                                    <img src="../../assets/images/post-edit.svg" alt="edit" width="24"
                                         class="d-inline-block">
                                </a>
                            <?php endif ?>
                            <?php if ($userIsAdmin): ?>
                                <button type="button" id="commentsButton" class="btn btn-light">
                                    <img src="../../assets/images/<?= $postIsCommentable ? 'comment-enabled' : 'comment-disabled' ?>.svg"
                                         alt="comments" width="24">
                                </button>
                            <?php endif ?>
                            <?php if ($userIsAdmin || $userIsAuthor): ?>
                                <button class="btn btn-light" onclick="" type="button" style="width: auto">
                                    <img src="../../assets/images/post-delete.svg"
                                         alt="delete" width="24"
                                         class="d-inline-block">
                                </button>
                            <?php endif ?>
                            <?php if (!$userIsAuthor && !$userIsAdmin): ?>
                                <button type="button" class="btn btn-light rounded-end" data-bs-toggle="modal" data-bs-target="#complaintWindow">
                                    <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
                                </button>
                                <div class="modal fade" id="complaintWindow" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Отправить жалобу</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php if ($visitorIsLogin && $post->getAuthor() !== $user->getLogin()): ?>
        <button type="button" id="like"
                style="background-color: <?= $post->isUserLikeIt($user->getId()) ? 'green' : 'grey' ?>">like
        </button>
        <button type="button" id="dislike"
                style="background-color: <?= $post->isUserDislikeIt($user->getId()) ? 'red' : 'grey' ?>">dislike
        </button>
    <?php endif ?>
    <!--Начало комментариев-->
    <div id="comments-permissions">
        <?php if (!$postIsCommentable): ?>
            <div class="alert alert-secondary text-center text-danger" role="alert">
                Комментарии запрещены.
            </div>
        <?php endif ?>
    </div>
    <h5 class="mt-2" id="commentsAmount"
        style="padding-left: 5%;color: #000000"><?= count($comments) . ' ' . NormalizeData::wordForm(count($comments), 'комментариев', 'комментарий', 'комментария') ?></h5>
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
