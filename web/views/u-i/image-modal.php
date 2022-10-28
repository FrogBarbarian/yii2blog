<?php
/**
 * @var \app\models\UploadForm $uploadForm
 */

declare(strict_types=1);

use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<!--TODO: Предпросмотр фото, дизайн окна-->
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
            'action' => Url::to('/post-u-i/upload-image'),
            'method' => 'post',
        ]); ?>
        <?= $form
            ->field($uploadForm, 'image')
            ->fileInput()
            ->label(false)
            ->error([
                'class' => 'text-danger small',
                'id' => 'imageErrorLabel',
            ])
        ?>
        <?= $form
            ->field($uploadForm, 'signature')
            ->input('text', ['class' => 'txt-input-basic'])
            ->label(false)
            ->error([
                'class' => 'text-danger small',
                'id' => 'signatureErrorLabel',
            ])
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