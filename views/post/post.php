<?php

declare(strict_types=1);

/**
 * @var \app\models\Post $post
 * @var \app\models\User $owner
 * @var \app\models\User $user
 * @var \app\models\Comment[] $comments
 * @var \app\models\CommentForm $commentForm
 * @var bool $visitorIsLogin
 */

use app\assets\PostAsset;
use app\components\CommentWidget;
use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;
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

PostAsset::register($this);

$views = "{$post->getViews()} " .
    NormalizeData::wordForm($post->getViews(),
        'просмотров',
        'просмотр',
        'просмотра',
    );
?>
<div class="mx-3 py-2">
    <?php if (Yii::$app->session->hasFlash('postFlash')): ?>
        <div class="alert alert-warning rounded-4" role="alert">
            <?= Yii::$app->session->getFlash('postFlash') ?>
        </div>
    <?php endif ?>
    <div class="card mx-auto rounded-0">
        <div class="card-header pt-3">
            <h5 class="card-title"><?= $post->getTitle() ?></h5>
        </div>
        <div class="card-body">
            <p class="card-text"><?= $post->getBody() ?></p>
            <hr>
            <?php foreach ($post->getTagsArray() as $tag): ?>
                <a class="tag-card suggested-tag" href="/tag/<?= $tag ?>">
                    <?= $tag ?>
                </a>
            <?php endforeach ?>
        </div>
        <div class="card-footer">
            <div class="hstack">
                <div class="col text-start small">
                    <?= $views ?>
                </div>
                <div class="col text-end small">
                    <?= NormalizeData::passedTime($post->getDatetime()) ?>
                    &nbsp;
                    <a class="author-link" href="/users/<?= $owner->getUsername() ?>">
                        <?= $post->getAuthor() ?>
                    </a>
                </div>
            </div>
            <div class="hstack">
                <div class="col">
                    <?php if ($visitorIsLogin && !$userIsAuthor): ?>
                        <button class="like-button" type="button" onclick="likeOrDislikePost(true)">
                            <img id="likePost"
                                 src="<?= IMAGES ?>other-buttons/like<?= $post->isUserAlreadyLikedPost($user->getId()) ? 'd' : '' ?>.svg"
                                 width="24" alt="like"/>
                        </button>
                    <?php endif ?>
                    <span id="post-rating">
                        <?= ConstructHtml::rating($post->getRating()) ?>
                    </span>
                    <?php if ($visitorIsLogin && !$userIsAuthor): ?>
                        <button class="like-button" onclick="likeOrDislikePost(false)">
                            <img id="dislikePost"
                                 src="<?= IMAGES ?>other-buttons/dislike<?= $post->isUserAlreadyDislikedPost($user->getId()) ? 'd' : '' ?>.svg"
                                 width="24" alt="dislike"/>
                        </button>
                    <?php endif ?>
                </div>
                <?php if ($visitorIsLogin): ?>
                    <div class="col text-end">
                        <!--Кнопки управления-->
                        <div class="btn-group">
                            <?php if ($userIsAuthor): ?>
                                <a class="btn-w-img"
                                   href="/edit-post?id=<?= $post->getId() ?>"
                                   data-bs-toggle="tooltip" data-bs-placement="top" title="Отредактировать">
                                    <img src="<?= IMAGES ?>other-buttons/post-edit.svg" alt="edit" width="24"
                                         class="d-inline-block">
                                </a>
                            <?php endif ?>
                            <?php if ($userIsAdmin): ?>
                                <button type="button" id="commentsButton" class="btn-w-img">
                                    <img src=".<?= IMAGES ?>other-buttons/<?= $postIsCommentable ? 'comment-enabled' : 'comment-disabled' ?>.svg"
                                         alt="comments">
                                </button>
                            <?php endif ?>
                            <?php if ($userIsAdmin || $userIsAuthor): ?>
                                <button class="btn-w-img" type="button" data-bs-toggle="modal"
                                        data-bs-target="#deletePostModal">
                                    <img src="<?= IMAGES ?>other-buttons/trash.svg"
                                         alt="delete" width="24"
                                         class="d-inline-block">
                                </button>
                                <div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Вы уверены, что хотите удалить пост?
                                                </h5>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p class="text-danger small fst-italic">
                                                    Будет удален пост и все комментарии, а также будет изменена
                                                    соответствующим образом статистика всех затронутых пользователей.
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn-basic" data-bs-dismiss="modal">
                                                    Назад
                                                </button>
                                                <button id="deletePostButton" type="button" class="btn-basic">
                                                    Удалить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>
                            <?php if (!$userIsAuthor && !$userIsAdmin): ?>
                                <button type="button"
                                        onclick="createComplaint('post', '<?= $post->getId() ?>')"
                                        class="btn-w-img">
                                    <img src="<?= IMAGES ?>other-buttons/create-complaint.svg" width="24" alt="create complaint"/>
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
    <h5 class="mt-2 px-5 text-black" id="commentsAmount">
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
            'errorOptions' => ['class' => 'text-danger small', 'id' => 'commentErrorLabel'],
            'template' => "{input}\n{label}\n{error}",
        ]; ?>
        <div class="window-basic mx-5 mb-2">
            <?php $activeForm = ActiveForm::begin([
                'id' => 'comment-form',
                'options' => [
                    'class' => 'p-1',
                ],
            ]) ?>
            <label for="commentInput">Напишите комментарий</label>
            <div class="div-input-basic" contenteditable="true" id="commentInput"></div>
            <?= $activeForm
                ->field($commentForm, 'comment', $options)
                ->hiddenInput([
                    'id' => 'commentValue',
                ])
                ->label(false)
            ?>
            <div class="d-flex justify-content-end">
                <button type="button" id="addComment" class="btn-basic">
                    Отправить
                </button>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif ?>
    <ul class="list-group px-5" id="comments">
        <?php if ($comments): ?>
            <?php foreach ($comments as $comment) {
                echo CommentWidget::widget(['user' => $user, 'comment' => $comment]);
            }
            ?>
        <?php endif ?>
    </ul>
    <?php if ($comments): ?>
        <span id="hideComments">
        Скрыть комментарии
    </span>
    <?php endif ?>
</div>
