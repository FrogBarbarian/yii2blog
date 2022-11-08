<?php
/**
 * @var \app\models\MessageForm $messageForm
 * @var \yii\web\View $this
 */

declare(strict_types=1);

use yii\widgets\ActiveForm;

$errorClass = 'text-danger small';
$this->registerJsFile('@js/message-form.js');
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
            'action' => '/u-i/send-message',
        ]) ?>
        <?= $form
            ->field($messageForm, 'recipientUsername')
            ->input('text', [
                'id' => 'recipientUsername',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Получатель',
            ])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'recipientUsernameErrorLabel']) ?>
        <ul class="list-group" id="suggestedRecipients"></ul>
        <?= $form
            ->field($messageForm, 'subject')
            ->input('text', [
                'id' => 'subjectInputField',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Тема письма',
            ])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'subjectErrorLabel']) ?>
        <div class='div-input-basic' id="contentInputField" contenteditable="true"></div>
        <?= $form
            ->field($messageForm, 'content')
            ->hiddenInput(['id' => 'contentHiddenInput'])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'contentErrorLabel']) ?>
        <div class="modal-window-footer">
            <button type="button" class="btn-basic" onclick="closeModalDiv()">
                Отмена
            </button>
            <button onclick="sendMessage()" type="button" class="btn-basic">
                Отправить
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>
