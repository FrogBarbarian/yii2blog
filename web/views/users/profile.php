<?php
/** @var array $user */
/** @var array $posts */
/** @var array $posts_tmp */


$this->title = 'Профиль';
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <!--TODO: Добавить функционал изменения личных данных-->
                <h5 class="card-title"><?= $user['login']?></h5>
                <p class="card-text">Email: <?=$user['email']?></p>
                <hr>
                <?php if ($posts): ?>
                    Список Ваших постов: <br>
                <!--TODO: Добавить пагинацию и выбор сколько постов отображать на странице-->
                <?php foreach ($posts as $post): ?>
                        <a href="/post?id=<?=$post['id']?>"><?=$post['title']?></a>
                        | Просмотров: <?=$post['viewed']?><br>
                <?php endforeach ?>
                <?php else: ?>
                    Похоже, что Вы еще не опубликовали ни одного поста. <br>
                <?php endif ?>
                <a type="button" href="/new-post" class="btn btn-outline-dark my-2">Создать новый пост</a>
                <?php if ($posts_tmp): ?>
                    <hr>
                    Посты, ожидающие проверки администрацией.
                    <br><br>
                    <div>
                        <?php foreach ($posts_tmp as $post_tmp): ?>
                                <?=$post_tmp['title']?>
                                | <?=$post_tmp['isNew'] ? 'Новый' : 'Отредактированный'?>
                                <br>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
