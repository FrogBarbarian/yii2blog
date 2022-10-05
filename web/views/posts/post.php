<?php

/** @var \app\models\Post $post */
/** @var \app\models\User $owner */
/** @var \app\models\User $user */
/** @var \app\models\Comment[] $comments */
/** @var bool $visitorIsLogin */

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
<ul class="list-group list-group-horizontal mb-1 col-3">
    <?php if ($user !== null): ?>
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
        <?php $activeForm = \yii\widgets\ActiveForm::begin(['options' => ['style' => 'width: 30px']]); ?>
        <input type="hidden" name="comments" value="1">
        <button type="submit" class="list-group-item list-group-item-action"
                data-bs-toggle="tooltip" data-bs-placement="top"
                title="<?= $postIsCommentable ? 'Запретить' : 'Разрешить' ?> комментарии" style="width: auto">
            <img src="../../assets/images/<?= $postIsCommentable ? 'comment-enabled' : 'comment-disabled' ?>.svg"
                 alt="comments" width="30" height="30"
                 class="d-inline-block">
        </button>
        <?php \yii\widgets\ActiveForm::end(); ?>
    <?php endif; ?>
</ul>
<div class="rounded-5" style="background-color: #84a2a6;">
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
                <?php foreach ($post->getTagsArray($post->getTags()) as $tag): ?>
                    <?= $tag ?>
                <?php endforeach ?>
            </div>
        </div>
        <div class="mb-2">
            <span style="color:
                        <?php
            if ($post->getRating() > 0): echo 'green';
            elseif ($post->getRating() < 0): echo 'red';
            else: echo 'grey';
            endif;
            ?>">
                <?= ($post->getRating() > 0 ? '+' : '') . $post->getRating() ?>
            </span>
            (
            <small style="color: green"><?= $post->getLikes() ?></small>
            /
            <small style="color: red"><?= $post->getDislikes() ?></small>
            )
            <?php if ($visitorIsLogin): ?>
            <p>
                <?php $activeForm = \yii\widgets\ActiveForm::begin([
                        'action' => \yii\helpers\Url::to('/interface/post')
                ]) ?>
                <input type="hidden" name="id" value="<?= $post->getId() ?>">
                <button <?= $post->isUserLikeIt(Yii::$app->session['id']) ? 'disabled' : '' ?> type="submit" name="postInterface" value="like">like</button>
                <button <?= $post->isUserDislikeIt(Yii::$app->session['id']) ? 'disabled' : '' ?> type="submit" name="postInterface" value="dislike">dislike</button>
                <?php \yii\widgets\ActiveForm::end() ?>
            </p>
            <?php endif ?>
        </div>
        <!--Начало комментариев-->
        <?php if (!$postIsCommentable): ?>
            <div class="alert alert-secondary text-center text-danger" role="alert">
                Комментарии запрещены.
            </div>
        <?php endif ?>

        <?php if ($comments): ?>
            <h5 style="padding-left: 5%"><?= count($comments) ?>
                комментариев</h5> <!--TODO: Функция окончания слова комментарии-->
            <?php if (count($comments) > 5): ?>
                <button style="height: 50px;margin-left: 5%;width: 90%" class="btn btn-dark mb-1" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false"
                        aria-controls="collapseExample">
                    Комментарии
                </button>
                <div class="collapse show" id="collapseExample" >
                <?php if ($visitorIsLogin && $userCanComment && $postIsCommentable) require 'widgets/comment-field.php' ?>
            <?php endif ?>
            <ul class="list-group" style="padding-left: 5%;padding-right: 5%">
                <?php foreach ($comments as $comment): ?>
                    <div class="list-group-item list-group-item-action mb-1">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><a
                                        href="/user?id=<?= $comment->getAuthorId() ?>"><?= $comment->getAuthor() ?></a>
                            </h5>
                            <small class="text-muted"><?= $comment->getDate() ?></small>
                            <!--TODO: Функция отсчета времени (минуты до часа, часы до дня, вчера, день/месяц - если год тот же, точная дата, год другой)-->
                        </div>
                        <p class="mb-1 text-break"><?= $comment->getComment() ?></p>
                        <!--TODO:Обработка и вывод текста с тегами-->
                        <small class="text-muted">
                            <a href="#">Like</a>
                            <a href="#">Dislike</a>
                            <?php if ($visitorIsLogin && $user->getLogin() === $comment->getAuthor()): ?>
                                <a href="#">Delete</a> <!--TODO: Удаление комментария (комментарий остается в бд, но параметр is_deleted ставиться в true)-->
                            <?php endif; ?>
                        </small>
                    </div>
                <?php endforeach ?>
            </ul>
            <?php if (count($comments) > 5): ?>
                </div>
            <?php endif ?>
        <?php elseif ($visitorIsLogin && $userCanComment && $postIsCommentable): ?>
            <div class="alert alert-secondary text-center" role="alert">
                Комментариев нет, вы можете быть первым.
            </div>
        <?php else: ?>
            <div class="alert alert-secondary text-center" role="alert">
                Комментариев нет.
            </div>
        <?php endif ?>
        <?php if ($visitorIsLogin && $postIsCommentable): ?>
            <?php if ($userCanComment): require 'widgets/comment-field.php' ?>
            <?php else: ?>
                <div class="alert alert-danger text-center" role="alert">
                    Вам запрещено комментировать.
                </div>
            <?php endif ?>
        <?php endif ?>
    </div>
</div>
