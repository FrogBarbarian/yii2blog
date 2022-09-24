<?php
/** @var array $post */
/** @var array $user */

$this->title = ($post['isNew'] ? 'Новый' : 'Редакция') . ' пост ' . $post['title'];
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?=$post['title']?></h5>
                <p class="card-text"><?=$post['body']?></p>
            </div>
            <div class="card-footer">
                <div>
                    Отправлен: <b>дата</b>. Автор - <?=$user['login']?>
                    <!--TODO: Функционал одобрения статьи-->
                    <a href="" class="btn btn-outline-dark">Одобрить</a>
                </div>
            </div>
        </div>
    </div>
</div>
