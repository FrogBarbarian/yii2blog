<?php
/** @var \app\models\User $user */
/** @var \app\models\Post[] $posts */
/** @var \app\models\PostTmp[] $postsTmp */


$this->title = 'Профиль';
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <!--TODO: Добавить функционал изменения личных данных-->
                <h5 class="card-title"><?= $user->getLogin()?></h5>
                <p class="card-text">Email: <?=$user->getEmail()?></p>
                <hr>
                <h5>Ваша статистика:</h5>
                <br>
                Количество написанных постов: <?=count($posts)?>
                <br>
                Количество просмотров: <!-- TODO: Функционал -->
                <br>
                Количество комментариев: <!-- TODO: Функционал -->
                <br>
                Ваш рейтинг: <!-- TODO: Функционал -->
                <hr>
                <?php if ($posts): ?>
                    Список Ваших постов: <br>
                <!--TODO: Добавить пагинацию и выбор сколько постов отображать на странице-->
                <?php foreach ($posts as $post): ?>
                        <a href="/post?id=<?=$post->getId()?>"><?=$post->getTitle()?></a>
                        | Просмотров: <?=$post->getViews()?><br>
                <?php endforeach ?>
                <?php else: ?>
                    Похоже, что Вы еще не опубликовали ни одного поста. <br>
                <?php endif ?>
                <a type="button" href="/new-post" class="btn btn-outline-dark my-2">Создать новый пост</a>
                <?php if ($postsTmp): ?>
                    <hr>
                    Посты, ожидающие проверки администрацией.
                    <br><br>
                    <div>
                        <?php foreach ($postsTmp as $postTmp): ?>
                                <?=$postTmp->getTitle()?>
                                | <?=$postTmp->getIsNew() ? 'Новый' : 'Отредактированный'?>
                                <br>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <?php if ($user->getIsAdmin()): ?>
                    <hr>
                    <!--TODO: Уведомления-->
                    Админский функционал
                    <br>
                    <a href="<?=ADMIN_PANEL?>" class="btn btn-outline-dark">Админ-панель</a>
                    <br>
                    <?php ?>
                    <!--Уведомления-->
                    <?php ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
