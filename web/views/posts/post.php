<?php
/** @var array $post */
/** @var array $user */

$this->title = $post['title'];
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <?php if (Yii::$app->session->hasFlash('postAlreadyUpdated')): ?>
        <div class="alert alert-warning rounded-4" role="alert">
            <?=Yii::$app->session->getFlash('postAlreadyUpdated')?>
        </div>
        <?php endif ?>
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?=$post['title']?></h5>
                <p class="card-text"><?=$post['body']?></p>
            </div>
            <div class="card-footer">
                <div>
                    Написан: <b>дата</b>. Просмотров: <?=$post['viewed']?>. Автор - <?=$post['author']?>
                </div>
            <?php if (Yii::$app->session->has('login')): ?>
                <?php if ($post['author'] == $user): ?>
                    <div>
                        <a type="button" href="/edit-post?id=<?=$post['id']?>" class="btn" style="float: right;">Отредактировать</a>
                    </div>
                <?php endif ?>
            <?php endif ?>
            </div>
        </div>
    </div>
</div>
