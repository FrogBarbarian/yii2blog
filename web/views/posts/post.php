<?php

/** @var \app\models\Post $post */
/** @var \app\models\User $owner */
/** @var \app\models\User $user */
/** @var \app\models\Comment[] $comments */
/** @var \app\models\CommentForm $model */
/** @var bool $visitorIsLogin */


use yii\widgets\ActiveForm;

$this->title = $post->getTitle();
?>

<div class="rounded-5" style="background-color: #84a2a6;">
    <div class="mx-3 py-5">
        <?php if (Yii::$app->session->hasFlash('postAlreadyUpdated')): ?>
        <div class="alert alert-warning rounded-4" role="alert">
            <?=Yii::$app->session->getFlash('postAlreadyUpdated')?>
        </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?=$post->getTitle()?></h5>
                <p class="card-text"><?=$post->getBody()?></p>
            </div>
            <div class="card-footer">
                <div>
                    Опубликован: <b><?=$post->getDate()?></b>.
                    Просмотров: <?=$post->getViews()?>.
                    <a href="/user?id=<?=$owner->getId()?>">
                        Автор - <?=$post->getAuthor()?>
                    </a>
                    <?php if ($user !== null && $post->getAuthor() === $user->getLogin()): ?>
                        <a type="button" href="/edit-post?id=<?=$post->getId()?>" class="btn" style="float: right;">Отредактировать</a>
                    <?php endif ?>
                </div>
                <hr>
                <?php foreach ($post->getTagsArray($post->getTags()) as $tag): ?>
                <?=$tag?>
                <?php endforeach ?>
            </div>
        </div>
        <?php if ($post->getIsCommentable()): ?>
            <?php if ($comments): ?>
                <!--TODO: Комментировать могут только зарегистрированные юзеры, смотреть комментарии могут все-->
                Форма комментария (если комментов больше 5?)
                Аккордеон, если комментариев больше 5
                Аккордеон с комментариями (развернуть/свернуть)
                <h4 style="padding-left: 5%"><?=count($comments)?> комментариев</h4> <!--TODO: Функция окончания слова комментарии-->
                <ul class="list-group" style="padding-left: 5%;padding-right: 5%;">
                    <?php foreach ($comments as $comment): ?>
                        <div class="list-group-item list-group-item-action mb-1">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><a href="/user?id=<?=$comment->getAuthorId()?>"><?=$comment->getAuthor()?></a></h5>
                                <small class="text-muted"><?=$comment->getDate()?></small> <!--TODO: Функция отсчета времени (минуты до часа, часы до дня, вчера, день/месяц - если год тот же, точная дата, год другой)-->
                            </div>
                            <p class="mb-1"><?=$comment->getComment()?></p>
                            <small class="text-muted">
                                <a href="#">Like</a>
                                <a href="#">Dislike</a>
                            </small>
                        </div>
                    <?php endforeach ?>
                </ul>
            <?php elseif ($visitorIsLogin && $user->getCanComment()): ?>
                <div class="alert alert-secondary text-center" role="alert">
                    Комментариев нет, вы можете быть первым.
                </div>
            <?php else: ?>
                <div class="alert alert-secondary text-center" role="alert">
                    Комментариев нет.
                </div>
            <?php endif ?>
            <?php if ($visitorIsLogin): ?>
                <?php if ($user->getCanComment()): ?>
                    <?php
                    $options = [
                        'options' => ['class' => 'form-floating', 'style' => 'justify-content: start;'],
                        'errorOptions' => ['class' => 'text-danger small'],
                        'template' => "{input}\n{label}\n{error}",
                    ];
                    $activeForm = ActiveForm::begin(['id' => 'comment-form']) ?>
                    <?=$activeForm
                    ->field($model, 'comment', $options)
                    ->textarea([
                        'placeholder' => 'comment',
                        'id' => 'commentArea',
                        'value' => '',
                        'style' => 'min-height: 150px',
                    ])
                    ->label('Комментарий', ['class' => false])
                    ?>
                    <input type="submit" class="btn">
                    <?php ActiveForm::end() ?>
                <?php else: ?>
                    <div class="alert alert-danger text-center" role="alert">
                        Вам запрещено комментировать.
                    </div>
                <?php endif ?>
            <?php endif ?>
        <?php else: ?>
            <div class="alert alert-secondary text-center text-danger" role="alert">
                Комментарии запрещены.
            </div>
        <?php endif ?>
    </div>
</div>
