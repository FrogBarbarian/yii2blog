<?php
/**
 * @var \app\models\MessageForm $model
 * @var \yii\web\View $this
 */

declare(strict_types=1);

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$errorOptions = ['class' => 'text-danger small help-block'];
$this->registerJsFile('@js/modals/message-form.js');
$this->registerJsFile('@js/utilities/notice.js');
$this->registerJs(<<<JS
    setMessageFormData();
JS
);

?>

<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window" style="width: 450px; max-width: 90vw">
        <div class="modal-window-header">
            Отправить сообщение
            <button type="button" class="btn-close" onclick="closeModalDiv()">
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'messageForm',
            'enableAjaxValidation' => true,
            'action' =>  Url::to('/message/send'),
        ]) ?>
        <?= $form
            ->field($model, 'recipientUsername')
            ->input('text', [
                'id' => 'recipientUsername',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Получатель',
            ])
            ->label(false)
            ->error($errorOptions) ?>
        <ul class="list-group" id="suggestedRecipients"></ul>
        <?= $form
            ->field($model, 'subject')
            ->input('text', [
                'id' => 'subjectInputField',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Тема письма',
            ])
            ->label(false)
            ->error($errorOptions) ?>
        <div class='div-input-basic' id="contentInputField" contenteditable="true"></div>
        <?= $form
            ->field($model, 'content')
            ->hiddenInput(['id' => 'contentHiddenInput'])
            ->label(false)
            ->error($errorOptions) ?>
        <div class="modal-window-footer">
            <button type="button" class="btn-basic" onclick="closeModalDiv()">
                Отмена
            </button>
            <button onclick="sendMessage()" type="submit" class="btn-basic">
                Отправить
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
