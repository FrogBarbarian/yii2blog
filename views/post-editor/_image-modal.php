<?php

declare(strict_types=1);

/**
 * @var \app\models\UploadForm $model
 */


use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

?>
<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window">
        <div class="modal-window-header">
            Добавить изображение
            <button type="button" class="btn-close" onclick="closeModalDiv()">
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'uploadImageForm',
            'options' => ['enctype' => 'multipart/form-data'],
            'enableClientValidation' => true,
            'action' => Url::to('/post-editor-ajax/upload-image'),
        ]); ?>
        <?=
        $form
            ->field($model, 'image')
            ->widget(FileInput::classname(), [
                'language' => 'ru',
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'mainClass' => 'd-flex justify-content-between',
                    'browseLabel' => 'Выбрать изображение',
                    'browseClass' => 'btn-basic',
                    'removeClass' => 'btn-basic',
                    'removeLabel' => 'Удалить',
                    'showClose' => false,
                    'fileActionSettings' => [
                        'indicatorNew' => '',
                        'indicatorNewTitle' => '',
                        'showZoom' => false,
                    ],
                    'showUpload' => false,
                    'showCaption' => false,
                ],
            ])
            ->error(['class' => 'text-danger small help-block'])
            ->label(false);
        ?>
        <?= $form
            ->field($model, 'signature')
            ->input('text', ['class' => 'txt-input-basic', 'placeholder' => 'Подпись'])
            ->label(false)
            ->error(['class' => 'text-danger small help-block'])
        ?>
        <div class="modal-window-footer">
            <button type="button" class="btn-basic" onclick="closeModalDiv()">
                Отмена
            </button>
            <button type="button" onclick="uploadImage()" class="btn-basic">
                Добавить изображение
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
