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
                'class' => 'form-control',
                'id' => 'titleInput',
                'value' => $title,
                'placeholder' => 'title',
            ])->label('Название', ['class' => false]) ?>
        <div class="post-body-input-group">
            <div>
                <button class="toolbar-button" type="button" onclick="bold()" title="Жирный (ctrl+b)">
                    <img src="<?= IMAGES ?>/post-toolbar/button-bold.svg" alt="bold">
                </button>
                <button class="toolbar-button" type="button" onclick="italic()" title="Курсив (ctrl+i)">
                    <img src="<?= IMAGES ?>/post-toolbar/button-italic.svg" alt="italic">
                </button>
                <button class="toolbar-button" type="button" onclick="underline()" title="Подчёркнутый (ctrl+u)">
                    <img src="<?= IMAGES ?>/post-toolbar/button-underline.svg" alt="underline">
                </button>
                <button class="toolbar-button" type="button" onclick="strikethrough()" title="Зачёркнутый (ctrl+s)">
                    <img src="<?= IMAGES ?>/post-toolbar/button-strikethrough.svg" alt="strikethrough">
                </button>
                <button class="toolbar-button" type="button" onclick="superscript()" title="Верхний индекс">
                    <img src="<?= IMAGES ?>/post-toolbar/button-superscript.svg" alt="sup">
                </button>
                <button class="toolbar-button" type="button" onclick="subscript()" title="Нижний индекс">
                    <img src="<?= IMAGES ?>/post-toolbar/button-subscript.svg" alt="sub">
                </button>
                <button class="toolbar-button" type="button" onclick="ul()" title="Маркированный список">
                    <img src="<?= IMAGES ?>/post-toolbar/button-unordered.svg" alt="ul">
                </button>
                <button class="toolbar-button" type="button" onclick="ol()" title="Нумерованный список">
                    <img src="<?= IMAGES ?>/post-toolbar/button-ordered.svg" alt="ol">
                </button>
                <button class="toolbar-button" type="button" onclick="hr()" title="Горизонтальная линия">
                    <img src="<?= IMAGES ?>/post-toolbar/button-hr.svg" alt="hr">
                </button>
                <button class="toolbar-button" type="button" onclick="quote()" title="Блок цитат">
                    <img src="<?= IMAGES ?>/post-toolbar/button-quotes.svg" alt="quotes">
                </button>
                <button class="toolbar-button" type="button" onclick="h5()" title="Заголовок">
                    <img src="<?= IMAGES ?>/post-toolbar/button-header.svg" alt="header">
                </button>
                <button class="toolbar-button" type="button" onclick="clearFormat()" title="Очистить форматирование">
                    <img src="<?= IMAGES ?>/post-toolbar/button-clear.svg" alt="clear format">
                </button>
                <button class="toolbar-button" type="button" onclick="linkModal()" title="Добавить ссылку">
                    <img src="<?= IMAGES ?>/post-toolbar/button-link.svg" alt="link">
                </button>
                <button class="toolbar-button" type="button" onclick="removeLink()" title="Удалить ссылку">
                    <img src="<?= IMAGES ?>/post-toolbar/button-rlink.svg" alt="remove link">
                </button>
                <button class="toolbar-button" type="button" onclick="imageModal()" title="Добавить изображение">
                    <img src="<?= IMAGES ?>/post-toolbar/button-image.svg" alt="add image">
                </button>
                <!--
                TODO: Изображение
                var url = prompt('Введите адрес изображения', 'https://snipp.ru/demo/526/image.jpg');
                document.execCommand('insertImage', false, url);
                -->
            </div>
            <hr>
            <div class="form-floating">
                <div oninput="edit(this)" id="inputBody" class="post-body-input" contenteditable="true">
                    <?= $body ?>
                </div>
                <label for="inputBody" style="color: grey; margin-top: -10px">Содержимое</label>
            </div>

        </div>
        <?= $activeForm->field($postInteractionsForm, 'body', ['errorOptions' => ['class' => 'text-danger small']])
            ->hiddenInput([
                'id' => 'bodyInput',
                'value' => $body,
            ]) ?>
        <hr>
        <div class="input-group">
            <span class="input-group-text">теги</span>
            <input oninput="fillTagField(this)" type="text" autocomplete="off" class="form-control" id="tagField">
            <button onclick="addTag()" class="btn btn-outline-secondary" type="button">+</button>
        </div>
        <ul class="list-group" id="suggestedTags"></ul>
        <div class="my-3" id="tagsArea"></div>
        <?= $activeForm->field($postInteractionsForm, 'tags', ['errorOptions' => ['class' => 'text-danger small']])
            ->hiddenInput([
                'value' => $tags,
            ]) ?>
    </div>
    <div class="card-footer">
        <div>
            <input type="submit" class="btn btn-outline-dark" value="Опубликовать">
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
