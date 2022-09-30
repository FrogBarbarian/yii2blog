<?php
/** @var \app\models\User $user */
/** @var \app\models\Post[] $posts */

/** @var \app\models\PostTmp[] $postsTmp */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$isOwn = (Yii::$app->requestedRoute === 'users/profile');
$this->title = $isOwn ? 'Профиль' : 'Пользователь - ' . $user->getLogin();
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <!--TODO: Добавить функционал изменения личных данных-->
                <h5 class="card-title"><?= $user->getLogin() ?></h5>
                <?php if ($isOwn): ?>
                    <div class="card-text">
                        <span>Email: <?= $user->getEmail() ?></span>
                        <span>
                            Профиль
                            <?php $activeForm = ActiveForm::begin([
                                'id' => 'change-visibility-form',
                                'action' => Url::to('/users/change-visibility')
                            ]) ?>
                            <?php if ($user->getIsHidden()): ?>
                                скрыт
                                <button type="submit" name="show" class="small btn-link btn btn-sm">открыть?</button>
                            <?php else: ?>
                                публичный
                                <button type="submit" name="hide" class="small btn-link btn btn-sm">скрыть?</button>
                            <?php endif ?>
                            <input type="hidden" name="id" value="<?= $user->getId() ?>">
                            <?php ActiveForm::end() ?>
                        </span>
                    </div>
                <?php endif ?>
                <?php if ($user->getIsHidden() && !$isOwn && !Yii::$app->session->has('admin')): ?>
                    <p class="text-danger">Профиль скрыт</p>
                <?php else: ?>
                    <hr>
                    <h5><?= $isOwn ? 'Ваша статистика:' : 'Статистика пользователя' ?></h5>
                    <br>
                    Количество написанных постов: <?= count($posts) ?>
                    <br>
                    Количество просмотров: <!-- TODO: Функционал -->
                    <br>
                    Количество комментариев: <!-- TODO: Функционал -->
                    <br>
                    Рейтинг: <!-- TODO: Функционал -->
                    <hr>
                    <?php if ($posts): ?>
                        <h6>Опубликованные посты:</h6>
                        <br>
                        <!--TODO: Добавить пагинацию и выбор сколько постов отображать на странице-->
                        <?php foreach ($posts as $post): ?>
                            <a href="/post?id=<?= $post->getId() ?>">
                                <?= $post->getPreview($post->getTitle(), 10, '') ?>
                            </a>
                            | Просмотров: <?= $post->getViews() ?>
                            <br>
                        <?php endforeach ?>
                    <?php else: ?>
                        <?= $isOwn ? 'Вы еще не опубликовали ни одного поста.' : $user->getLogin() . ' еще не написал ни одного поста.' ?>
                        <br>
                    <?php endif ?>
                <?php endif ?>

                <?php if ($isOwn): ?>
                    <a type="button" href="/new-post" class="btn btn-outline-dark my-2">Создать новый пост</a>
                    <?php if ($postsTmp): ?>
                        <hr>
                        Посты, ожидающие проверки администрацией.
                        <br>
                        <br>
                        <div>
                            <?php foreach ($postsTmp as $postTmp): ?>
                                <?= $postTmp->getTitle() ?>
                                | <?= $postTmp->getIsNew() ? 'Новый' : 'Отредактированный' ?>
                                <br>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>
                    <?php if ($user->getIsAdmin()): ?>
                        <hr>
                        <!--TODO: Уведомления-->
                        Админский функционал
                        <br>
                        <a href="<?= ADMIN_PANEL ?>" class="btn btn-outline-dark">Админ-панель</a>
                        <br>
                        <?php ?>
                        <!--Уведомления-->
                        <?php ?>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
