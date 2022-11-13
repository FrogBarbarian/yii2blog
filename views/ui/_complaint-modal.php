<?php

declare(strict_types=1);

/**
 * @var \app\models\ComplaintForm $model
 * @var string $objectType
 * @var string $objectId
 * @var \yii\web\View $this
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->registerJsFile('@js/modals/complaint-form.js');
$this->registerJsFile('@js/utilities/notice.js');
$this->registerJS(<<<JS
    setComplaintFormData();
JS
);
?>
<div class='modal-window-back' id='modalWindow' tabindex='-1'>
    <div class='modal-window'>
        <div class='modal-window-header'>
            Отправить жалобу
            <button type='button' onclick='closeModalDiv()' class='btn-close'>
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'complaintForm',
            'enableAjaxValidation' => true,
            'action' => Url::to('/complaint/send'),
        ]) ?>
        <span class="small">
            Изложите суть жалобы. Избегайте размытых выражений и конкретизируйте.
            Уложитесь в 250 символов.
        </span>
        <div id="complaintInputField" class="div-input-basic" contenteditable="true"></div>
        <?= $form
            ->field($model, 'complaint')
            ->hiddenInput(['id' => 'complaintHiddenInput'])
            ->label(false)
            ->error(['class' => 'text-danger small help-block']) ?>
        <?= $form
            ->field($model, 'objectType')
            ->hiddenInput(['value' => $objectType])
            ->label(false)
            ->error(false) ?>
        <?= $form
            ->field($model, 'objectId')
            ->hiddenInput(['value' => $objectId])
            ->label(false)
            ->error(false) ?>
        <div class='modal-window-footer'>
            <button type='button' onclick='closeModalDiv()' class='btn-basic'>
                Отмена
            </button>
            <button type='submit' onclick="sendComplaint()" class='btn-basic'>
                Отправить
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
