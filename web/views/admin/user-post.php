<?php
/** @var \app\models\PostTmp $post */
/** @var \app\models\PostInteractionsForm $model */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = ($post['isNew'] ? 'Новый' : 'Отредактированный') . ' пост ' . $post['title'];
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;">
    <div class="mx-3 py-5">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <div class="card-body">
                <h5 class="card-title"><?=$post->getTitle()?></h5>
                <p class="card-text"><?=$post->getBody()?></p>
            </div>
            <div class="card-footer">
                <div>
                    Отправлен: <b>дата</b>. Автор - <?=$post->getAuthor()?>
                    <?php $activeForm = ActiveForm::begin([
                        'id' => 'post-confirm-form',
                        'action' => Url::to(ADMIN_PANEL. '/confirm'),
                    ]) ?>
                    <?= $activeForm->field($model, 'id')
                        ->hiddenInput(['value' => $post->getId()])
                        ->label(false)->error(false) ?>
                    <!--TODO: Функционал одобрения статьи-->
                    <button type="submit" class="btn btn-outline-dark">Одобрить</button>
                    <?php ActiveForm::end() ?>
                    <!--TODO: Функционал неодобрения статьи (открывается модальное окно с комментарием создателю)-->
                    <a href="" class="btn btn-outline-dark">Отказать</a>
                    <?php if (!$post['isNew']): ?>
                        <!--TODO: Реализовать ссылку на оригинал-->
                        <a href="/" class="btn btn-outline-dark">Открыть оригинал</a>
                    <?php endif ?>
                    <hr>
                    <h5>Тэги:</h5>
                    <?php foreach ($post->getTagsArray($post->getTags()) as $tag): ?>
                        <b class="mx-1"><?=$tag?></b>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
