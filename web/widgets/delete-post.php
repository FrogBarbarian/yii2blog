<?php
/** @var \app\models\Post $post **/
?>
<div class="modal fade" id="deletePost" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Вы уверены, что хотите удалить пост?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5>Статистика поста</h5>
                <p><?= $post->getViews() ?> просмотра(ов)</p>
                <div>
                    <span>Рейтинг - <?= $post->getRating() ?></span>
                    <span>Лайков - <?= $post->getLikes() ?></span>
                    <span>Дизлайков - <?= $post->getDislikes() ?></span>
                    <p class="text-warning">Будет удален пост и все комментарии, а также будет изменена соответствующим образом статистика всех затронутых пользователей.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
                <?php $activeForm = \yii\widgets\ActiveForm::begin(['action' => \yii\helpers\Url::to('/posts/delete')]) ?>
                    <input type="hidden" name="id" value="<?= $post->getId() ?>">
                    <button type="submit" name="delete" class="btn btn-primary">Удалить</button>
                <?php \yii\widgets\ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>