<?php
/**
 * @var \app\models\UploadForm $model
 */

declare(strict_types=1);

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
            'action' => Url::to('/post-editor/upload-image'),
            'method' => 'post',
        ]); ?>
        <?=
        $form
            ->field($model, 'image')
            ->widget(FileInput::classname(), [
                'language' => 'ru',
                'options' => ['accept' => 'image/*'],
                'pluginOptions' => [
                    'mainClass' => 'd-flex justify-content-between',
                    'browseLabel' => 'Выберите изображение',
                    'removeLabel' => '',
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
            ->error([
                'class' => 'text-danger small help-block',
                'id' => 'signatureErrorLabel',])
            ->label(false);
        ?>
        <?= $form
            ->field($model, 'signature')
            ->input('text', ['class' => 'txt-input-basic', 'placeholder' => 'Подпись'])
            ->label(false)
            ->error([
                'class' => 'text-danger small help-block',
                'id' => 'signatureErrorLabel',
            ])
        ?>
        <div class="modal-window-footer">
            <button type="button" class="btn-basic" onclick="closeModalDiv()">
                Отмена
            </button>
            <button type="submit" onclick="uploadImage()" class="btn-basic">
                Добавить изображение
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
