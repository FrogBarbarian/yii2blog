<?php
/**
 * @var \app\models\Post $post
 * @var \app\models\User $owner
 * @var \app\models\User $user
 * @var \app\models\Comment[] $comments
 * @var \app\models\CommentForm $commentForm
 * @var bool $visitorIsLogin
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
        <div class="card-header pt-3">
            <h5 class="card-title"><?= $post->getTitle() ?></h5>
        </div>
        <div class="card-body">
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
                <div class="col">
                    <?php if ($visitorIsLogin && !$userIsAuthor): ?>
                        <button class="rounded-circle" type="button" id="likePost"
                                style="background-color: <?= $post->isUserLikeIt($user->getId()) ? 'green' : '#f7f7f7' ?>">
                            <img src="/assets/images/like.svg" width="24" alt="like"/>
                        </button>
                    <?php endif ?>
                    <span id="post-rating">
                        <?= ConstructHtml::rating($post->getRating()) ?>
                    </span>
                    <?php if ($visitorIsLogin && !$userIsAuthor): ?>
                        <button class="rounded-circle" id="dislikePost"
                                style="background-color: <?= $post->isUserDislikeIt($user->getId()) ? 'red' : '#f7f7f7' ?>">
                            <img src="/assets/images/dislike.svg" width="24" alt="dislike"/>
                        </button>
                    <?php endif ?>
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
                                <button class="btn btn-light" type="button" style="width: auto" data-bs-toggle="modal"
                                        data-bs-target="#deletePost">
                                    <img src="../../assets/images/post-delete.svg"
                                         alt="delete" width="24"
                                         class="d-inline-block">
                                </button>
                                <?php require 'widgets/delete-post.php' ?>
                            <?php endif ?>
                            <?php if (!$userIsAuthor && !$userIsAdmin): ?>
                                <button type="button"
                                        onclick="createComplaint('post', <?= $post->getId() ?>, <?= $user->getId() ?>)"
                                        class="btn btn-light rounded-end">
                                    <img src="/assets/images/create-complaint.svg" width="24" alt="create complaint"/>
                                </button>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <!--Начало комментариев-->
    <div id="comments-permissions">
        <?php if (!$postIsCommentable): ?>
            <div class="alert alert-danger text-center" role="alert">
                Комментарии запрещены.
            </div>
        <?php elseif (!$visitorIsLogin): ?>
            <div class="alert alert-danger text-center" role="alert">
                Авторизуйтесь, чтобы комментировать.
            </div>
        <?php elseif (!$userCanComment): ?>
            <div class="alert alert-danger text-center" role="alert">
                Вам запрещено комментировать.
            </div>
        <?php endif ?>
    </div>
    <h5 class="mt-2" id="commentsAmount" style="padding-left: 5%;color: #000000">
        <?= count($comments) . ' ' . NormalizeData::wordForm(
            count($comments),
            'комментариев',
            'комментарий',
            'комментария',
        ); ?>
    </h5>
    <?php if ($visitorIsLogin && $userCanComment && $postIsCommentable): ?>
        <?php $options = [
            'options' => ['class' => 'form-floating'],
            'errorOptions' => ['class' => 'text-danger small', 'id' => 'errorLabel'],
            'template' => "{input}\n{label}\n{error}",
        ]; ?>
        <div class="rounded-2" style="background-color: white;margin-left: 5%;margin-right: 5%;margin-bottom: 1%">
            <?php $activeForm = ActiveForm::begin([
                'id' => 'comment-form',
                'options' => [
                    'style' => 'width: 100%;padding: 1%',
                ],
                'enableAjaxValidation' => true,
                'validateOnType' => true,
                'action' => Url::to('/posts/add-comment'),
                'validationUrl' => Url::to('/posts/add-comment'),
            ]) ?>
            <?= $activeForm
                ->field($commentForm, 'comment', $options)
                ->textarea([
                    'placeholder' => 'comment',
                    'id' => 'commentArea',
                    'style' => 'min-height: 150px',
                ])
                ->label('Комментарий', ['class' => false])
            ?>
            <?= $activeForm
                ->field($commentForm, 'postId')
                ->hiddenInput(['value' => $post->getId()]) ?>
            <button type="button" id="addComment" class="btn btn-dark mt-1">Отправить</button>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif ?>
    <ul class="list-group" id="comments" style="padding-left: 5%;padding-right: 5%">
        <?= $comments ? ConstructHtml::comments($comments) : '' ?>
    </ul>
</div>
