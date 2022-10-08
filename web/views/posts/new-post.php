<?php
/** @var \app\models\PostInteractionsForm $model */
/** @var \app\models\Post $post */

use yii\widgets\ActiveForm;

$isEdit = isset($post);
if (isset($_POST['PostInteractionsForm'])) {
    $title = $_POST['PostInteractionsForm']['title'];
    $body = $_POST['PostInteractionsForm']['body'];
    $tags = $_POST['PostInteractionsForm']['tags'];
} elseif ($isEdit) {
    $title = $post->getTitle();
    $body = $post->getBody();
    $tags = $post->getTags();
} else {
    $title = '';
    $body = '';
    $tags = '';
}
$this->title = $isEdit ? 'Редактирование' : 'Новый пост';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
?>


    <div class="card-header alert alert-warning rounded-4 small" role="alert">
        Название должно содержать от 30 до 150 символов. <br>
        Текст поста должен содержать от 300 до 10000 символов. <br>
        Остальные правила - бла бла бла бла.
    </div>
    <?php $activeForm = ActiveForm::begin([
        'id' => 'new-post-form',
    ]) ?>
    <div class="card-body">
        <?= $activeForm->field($model, 'title', $options)
            ->input('text', [
                'class' => 'form-control placeholder-wave',
                'id' => 'titleInput',
                'value' => $title,
                'placeholder' => 'title',
                'style' => 'background-color: #899aa2;',
            ])->label('Название', ['class' => false]) ?>
        <?= $activeForm->field($model, 'body', ['errorOptions' => ['class' => 'text-danger small']])
            ->textarea([
                'class' => 'form-control',
                'id' => 'bodyInput',
                'value' => $body,
                'placeholder' => 'Содержание',
                'style' => 'background-color: #899aa2;min-height: 50vh;',
            ])->label(false) ?>
    </div>
    <hr>
<!--TODO: механизм тегов-->
    <?php if ($tags): ?>
        <h5>Тэги:</h5>
        <?php foreach ($post->getTagsArray($tags) as $tag): ?>
            <b class="mx-1"><?=$tag?></b>
        <?php endforeach ?>
    <?php endif ?>
    <div class="card-footer">
        <div>
            <input type="submit" class="btn btn-outline-dark" value="Опубликовать">
        </div>
    </div>
    <?php ActiveForm::end() ?>