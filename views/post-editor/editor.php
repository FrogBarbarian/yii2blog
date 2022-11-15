<?php

declare(strict_types=1);

/**
 * @var \app\models\PostEditorForm $model
 * @var \app\models\Post $post
 * @var bool $isNew
 * @var \yii\web\View $this
 */


use yii\helpers\Url;
use yii\widgets\ActiveForm;

$id = $isNew ? null : $post->getId();
$title = $isNew ? '' : $post->getTitle();
$body = $isNew ? '' : $post->getBody();
$tags = $isNew ? '' : $post->getTags();
$action = $isNew ? 'create' : 'update';
$this->title = $isNew ? 'Новый пост' : 'Редактирование';

$this->registerJsFile('@js/post-editor.js');
$errorOptions = ['class' => 'text-danger small help-block'];
?>
<div class="window-basic">
    <div class="alert alert-warning small">
        Название должно содержать от 30 до 150 символов.
        Текст поста должен содержать от 300 до 10000 символов. <br>
        Рекомендуется использовать не более 5 тегов и не менее 2. <br>
        Остальные правила - бла бла бла бла.
    </div>
    <?php $form = ActiveForm::begin([
        'id' => 'postEditorForm',
        'action' => Url::to(["/post-editor-ajax/$action", 'id' => $id]),
        'method' => 'post',
        'enableAjaxValidation' => true,
    ]) ?>
    <div class="card-body">
        <?= $form
            ->field($model, 'title')
            ->input('text', [
                'class' => 'txt-input-basic',
                'autofocus' => true,
                'value' => $title,
            ])->label('Название')
            ->error($errorOptions) ?>
        <div class="post-body-input-group">
            <div id="toolbar">
                <div id="buttons">
                    <button class="toolbar-button" type="button" onclick="bold()" title="Жирный (ctrl+b)">
                        <img src="<?= IMAGES ?>post-toolbar/button-bold.svg" alt="bold">
                    </button>
                    <button class="toolbar-button" type="button" onclick="italic()" title="Курсив (ctrl+i)">
                        <img src="<?= IMAGES ?>post-toolbar/button-italic.svg" alt="italic">
                    </button>
                    <button class="toolbar-button" type="button" onclick="underline()" title="Подчёркнутый (ctrl+u)">
                        <img src="<?= IMAGES ?>post-toolbar/button-underline.svg" alt="underline">
                    </button>
                    <button class="toolbar-button" type="button" onclick="strikethrough()" title="Зачёркнутый (ctrl+s)">
                        <img src="<?= IMAGES ?>post-toolbar/button-strikethrough.svg" alt="strikethrough">
                    </button>
                    <button class="toolbar-button" type="button" onclick="superscript()" title="Верхний индекс">
                        <img src="<?= IMAGES ?>post-toolbar/button-superscript.svg" alt="sup">
                    </button>
                    <button class="toolbar-button" type="button" onclick="subscript()" title="Нижний индекс">
                        <img src="<?= IMAGES ?>post-toolbar/button-subscript.svg" alt="sub">
                    </button>
                    <button class="toolbar-button" type="button" onclick="ul()" title="Маркированный список">
                        <img src="<?= IMAGES ?>post-toolbar/button-unordered.svg" alt="ul">
                    </button>
                    <button class="toolbar-button" type="button" onclick="ol()" title="Нумерованный список">
                        <img src="<?= IMAGES ?>post-toolbar/button-ordered.svg" alt="ol">
                    </button>
                    <button class="toolbar-button" type="button" onclick="hr()" title="Горизонтальная линия">
                        <img src="<?= IMAGES ?>post-toolbar/button-hr.svg" alt="hr">
                    </button>
                    <button class="toolbar-button" type="button" onclick="quote()" title="Блок цитат">
                        <img src="<?= IMAGES ?>post-toolbar/button-quotes.svg" alt="quotes">
                    </button>
                    <button class="toolbar-button" type="button" onclick="h5()" title="Заголовок">
                        <img src="<?= IMAGES ?>post-toolbar/button-header.svg" alt="header">
                    </button>
                    <button class="toolbar-button" type="button" onclick="clearFormat()"
                            title="Очистить форматирование">
                        <img src="<?= IMAGES ?>post-toolbar/button-clear.svg" alt="clear format">
                    </button>
                    <button class="toolbar-button" type="button" onclick="linkModal()" title="Добавить ссылку">
                        <img src="<?= IMAGES ?>post-toolbar/button-link.svg" alt="link">
                    </button>
                    <button class="toolbar-button" type="button" onclick="removeLink()" title="Удалить ссылку">
                        <img src="<?= IMAGES ?>post-toolbar/button-rlink.svg" alt="remove link">
                    </button>
                    <button class="toolbar-button" type="button" onclick="imageModal()" title="Добавить изображение">
                        <img src="<?= IMAGES ?>post-toolbar/button-image.svg" alt="add image">
                    </button>
                </div>
            </div>
            <hr>
            <label for="inputBody">Содержимое</label>
            <div oninput="edit(this)" id="inputBody" class="div-input-basic" contenteditable="true">
                <?= $body ?>
            </div>
        </div>
        <?= $form->field($model, 'body')
            ->hiddenInput([
                'id' => 'bodyInput',
                'value' => $body,
            ])->label(false)
            ->error($errorOptions) ?>
        <hr>
        <label for="tagField">Теги</label>
        <div class="d-flex">
            <input oninput="fillTagField(this)" type="text" autocomplete="off" class="txt-input-basic" id="tagField">
            <button onclick="addTag()" class="toolbar-button my-1 ms-1" type="button">+</button>
        </div>

        <ul class="list-group" id="suggestedTags"></ul>
        <div class="my-3" id="tagsArea"></div>
        <?= $form->field($model, 'tags')
            ->hiddenInput([
                'id' => 'tagsInput',
                'value' => $tags,
            ])->label(false)
            ->error($errorOptions) ?>
    </div>
    <div class="card-footer">
        <div>
            <button type="submit" name="submitPost" class="btn-basic">Опубликовать</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
