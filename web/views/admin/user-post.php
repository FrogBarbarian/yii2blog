<?php
/** @var array $post */
/** @var array $user */
/** @var \app\models\Admin $model */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                    <?php $activeForm = ActiveForm::begin([
                        'id' => 'user-confirm-form',
                        'action' => Url::to('/admin/confirm'),
                    ]) ?>
                    <?= $activeForm->field($model, 'id')
                        ->hiddenInput(['value' => $post['id']])
                        ->label(false)->error(false) ?>
                    <button type="submit" class="btn btn-outline-dark">Одобрить</button>
                    <?php ActiveForm::end() ?>
                    <!--TODO: Функционал неодобрения статьи (открывается модальное окно с комментарием создателю)-->
                    <a href="" class="btn btn-outline-dark">Отказать</a>
                </div>
            </div>
        </div>
    </div>
</div>
