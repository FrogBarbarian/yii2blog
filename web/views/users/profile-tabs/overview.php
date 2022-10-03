<?php
/** @var bool $isOwn */
/** @var \app\models\User $user */
/** @var \app\models\Statistics $statistics */
/** @var \app\models\Post[] $posts */
/** @var \app\models\PostTmp[] $postsTmp */

?>

<?php if ($isOwn): ?>
    <div class="card-text">
        Профиль <?=$user->getIsHidden() ? 'скрытый' : 'публичный' ?>
    </div>
<?php endif ?>

<?php if ($user->getIsHidden() && !$isOwn && !Yii::$app->session->has('admin')): ?>
    <p class="text-danger">Профиль скрыт</p>
<?php else: ?>
    <hr>
    <h4 class="text-center"><?= $isOwn ? 'Статистика' : 'Статистика пользователя'?></h4>
    <ul class="list-group">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Написано постов
            <span class="badge bg-primary rounded-pill"><?=$statistics->getPosts()?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Просмотров
            <span class="badge bg-primary rounded-pill"><?=$statistics->getViews()?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Комментариев
            <span class="badge bg-primary rounded-pill"><?=$statistics->getComments()?></span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Лайков <!-- TODO: Функционал -->
            <span class="badge bg-success rounded-pill">150</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            Дизлайков <!-- TODO: Функционал -->
            <span class="badge bg-danger rounded-pill">200</span>
        </li>
    </ul>
    <hr>
    <?php if ($posts): ?>
        <h4 class="text-center">Опубликованные посты</h4>
        <!--TODO: Добавить пагинацию и выбор сколько постов отображать на странице-->
        <div class="list-group">
            <?php foreach ($posts as $post): ?>
                <a href="/post?id=<?=$post->getId()?>" class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?=$post->getPreview($post->getTitle(), 50, '')?></h5>
                        <small>Написан <?=$post->getDate()?> просмотров: <?=$post->getViews()?></small>
                    </div>
                    <p class="mb-1"><?=$post->getPreview($post->getBody(), 150)?></p>
                </a>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <?=$isOwn ? 'Вы еще не опубликовали ни одного поста.' : "{$user->getLogin()} еще не написал ни одного поста."?>
    <?php endif ?>
<?php endif ?>

<?php if ($isOwn): ?>
    <?php if ($postsTmp && !Yii::$app->session->has('admin')): ?>
        <hr>
        <h5 class="text-center">Посты, ожидающие проверки администрацией.</h5>
        <div class="list-group">
            <?php foreach ($postsTmp as $postTmp): ?>
                <!--TODO: Ссылка на tmp пост-->
                <a href="#" class="list-group-item list-group-item-action">
                    <?=$postTmp->getTitle() ?>
                    | <?=$postTmp->getIsNew() ? 'Новый' : 'Отредактированный' ?>
                </a>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if ($user->getIsAdmin()): ?>
        <hr>
        <!--TODO: Уведомления-->
        <h5 class="text-center">Админский функционал</h5>
        <ul class="list-group mb-1">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?=$postsTmp ? 'Посты для проверки' : 'Постов для проверки нет' ?>
                <span class="badge bg-primary rounded-pill"><?=$postsTmp ? count($postsTmp) : ''?></span>
            </li>
            <!--TODO: Добавить функционал жалоб-->
            <li class="list-group-item d-flex justify-content-between align-items-center">
                "Жалобы" ?? "Новых жалоб нет"
                <span class="badge bg-primary rounded-pill">?? Integer</span>
            </li>
        </ul>
        <a href="<?= ADMIN_PANEL ?>" class="btn btn-outline-dark">Админ-панель</a>
        <br>
    <?php endif ?>
<?php endif ?>
