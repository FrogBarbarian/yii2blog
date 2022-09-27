<?php

/** @var \app\models\Posts $post */
/** @var string $user */

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
                <h5 class="card-title"><?=$post->getTitle()?></h5>
                <p class="card-text"><?=$post->getBody()?></p>
            </div>
            <div class="card-footer">
                <div>
                    Написан: <b>дата</b>. Просмотров: <?=$post->getViews()?>. Автор - <?=$post->getAuthor()?>
                </div>
                <?php if ($post->getAuthor() == $user): ?>
                    <div>
                        <a type="button" href="/edit-post?id=<?=$post->getId()?>" class="btn" style="float: right;">Отредактировать</a>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
