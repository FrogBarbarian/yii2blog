<?php
/** @var \app\models\PostInteractionsForm $postInteractionsForm */
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
<script src="../../assets/js/post-edit.js"></script>
<div class="card mx-auto rounded-0">
    <div class="card-header alert alert-warning small" role="alert">
        Название должно содержать от 30 до 150 символов.
        Текст поста должен содержать от 300 до 10000 символов. <br>
        Рекомендуется использовать не более 5 тегов и не менее 2. <br>
        Остальные правила - бла бла бла бла.
    </div>
    <?php $activeForm = ActiveForm::begin([
        'id' => 'new-post-form',
    ]) ?>
    <div class="card-body">
        <?= $activeForm->field($postInteractionsForm, 'title', $options)
            ->input('text', [
                'class' => 'form-control placeholder-wave',
                'id' => 'titleInput',
                'value' => $title,
                'placeholder' => 'title',
                'style' => 'background-color: #f7f7f7;',
            ])->label('Название', ['class' => false]) ?>
        <?= $activeForm->field($postInteractionsForm, 'body', ['errorOptions' => ['class' => 'text-danger small']])
            ->textarea([
                'class' => 'form-control',
                'id' => 'bodyInput',
                'value' => $body,
                'placeholder' => 'Содержание',
                'style' => 'background-color: #f7f7f7;min-height: 5vh;',
            ])->label(false) ?>
        <?= $activeForm->field($postInteractionsForm, 'tags', ['errorOptions' => ['class' => 'text-danger small']])
            ->hiddenInput([
                    'value' => $tags,
            ])?>
    </div>
    <hr>
    <div class="input-group px-3">
        <span class="input-group-text">теги</span>
        <input oninput="fillTagField(this)" type="text" autocomplete="off" class="form-control" id="tagField">
        <button onclick="addTag()" class="btn btn-outline-secondary" type="button">+</button>
    </div>
    <ul class="list-group px-3" id="suggestedTags"></ul>
    <div class="m-3" id="tagsArea"></div>
    <div class="card-footer">
        <div>
            <input type="submit" class="btn btn-outline-dark" value="Опубликовать">
        </div>
    </div>
    <?php ActiveForm::end() ?>
    <div style="border: 2px orange solid; min-height: 8rem;padding: 5px;">
        <button>B</button>
        <button>U</button>
        <button>I</button>
        <hr>
        <div id="inputBody">
            <span onclick="showInput(this)" style="border: 1px solid;cursor: pointer;display: block;min-height: 5rem"><?= $body ?></span>
        </div>
    </div>
</div>
