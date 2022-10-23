<?php
/**
 * @var \app\models\UploadForm $uploadForm
 */

declare(strict_types=1);

use yii\widgets\ActiveForm;
?>
<!--TODO: DO IT-->
<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window">
        <div class="modal-window-header">
            Добавить изображение
            <button type="button" class="btn-close" onclick="closeModalDiv()">
            </button>
        </div>
        <?php
        $form = ActiveForm::begin(['id' => 'uploadFileForm']);
        ?>
        <?= $form
            ->field($uploadForm, 'imageFile')
            ->fileInput() ?>
        <div class="modal-window-footer">
            <button type="button" class="toolbar-button me-1" onclick="closeModalDiv()"
                    style="width: auto; font-weight: lighter">
                Отмена
            </button>
            <button type="button" onclick="uploadImage()" class="toolbar-button" style="width: auto; font-weight: lighter">
                Добавить изображение
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>