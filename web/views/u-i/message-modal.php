<?php
/**
 * @var \app\models\MessageForm $messageForm
 */

declare(strict_types=1);

use yii\widgets\ActiveForm;

$errorClass = 'text-danger small';
?>
<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window" style="width: 450px; max-width: 90vw">
        <div class="modal-window-header">
            Отправить сообщение
            <button type="button" class="btn-close" onclick="closeModalDiv()">
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => false,
            'id' => 'messageForm',
            'action' => '/u-i/send-message',
        ]) ?>
        <?= $form
            ->field($messageForm, 'recipientUsername')
            ->input('text', [
                'class' => 'message-field',
                'autocomplete' => 'off',
                'placeholder' => 'Получатель',
            ])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'recipientUsernameErrorLabel']) ?>
        <ul class="list-group" id="suggestedRecipients"></ul>
        <?= $form
            ->field($messageForm, 'subject')
            ->input('text', [
                'class' => 'message-field',
                'autocomplete' => 'off',
                'placeholder' => 'Тема письма',
            ])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'subjectErrorLabel']) ?>
        <div class='div-input' id="contentInputField" contenteditable="true"></div>
        <?= $form
            ->field($messageForm, 'content')
            ->hiddenInput(['id' => 'contentHiddenInput'])
            ->label(false)
            ->error(['class' => $errorClass, 'id' => 'contentErrorLabel']) ?>
        <div class="modal-window-footer">
            <button type="button" class="toolbar-button me-1" onclick="closeModalDiv()"
                    style="width: auto; font-weight: lighter">
                Отмена
            </button>
            <button onclick="sendMessage()" type="button" class="toolbar-button"
                    style="width: auto; font-weight: lighter">
                Отправить
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>


<script>
    /**
     * Поле ввода содержания письма.
     */
    contentInputField = document.getElementById('contentInputField');
    /**
     * Скрытое поле для содержания письма.
     */
    contentHiddenInput = document.getElementById('contentHiddenInput');
    recipientInputField = document.getElementById('messageform-recipientusername');
    recipientInputField.oninput = () => {
        getRecipients(recipientInputField.value);
    }
    contentInputField.oninput = () => {
        contentHiddenInput.value = contentInputField.innerText;
    }

    function rewriteData() {
        contentInputField = document.getElementById('contentInputField');
        contentHiddenInput = document.getElementById('contentHiddenInput');
        recipientInputField.oninput = () => {
            getRecipients(recipientInputField.value);
        }
        contentInputField.oninput = () => {
            contentHiddenInput.value = contentInputField.innerText;
        }
    }

    function getRecipients(data) {
        $.ajax({
            url: '/u-i/get-users',
            cache: false,
            data: {data: data},
            success: function (response) {
                let field = $('#suggestedRecipients');
                field.html('')

                if (response === false) {
                    return false;
                }

                response.forEach((user) => {
                    field.html(field.html() +
                        '<li class="message-suggested-user" onclick="addRecipient(\'' +
                        user['username'] +
                        '\')">' +
                        user['username'] +
                        '</li>'
                    );
                });
            }
        })
    }

    function addRecipient(username) {
        $('#suggestedRecipients').html('');
        recipientInputField.value = username;
    }

    function sendMessage() {
        let form = $('#messageForm');
        let formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            cache: false,
            data: formData,
            success: function (response) {
                if (response === true) {
                    closeModalDiv();
                    alert('Сообщение успешно отправлено.')

                    return true;
                }
                let errorLabels = form[0].querySelectorAll('[id$=ErrorLabel]');

                for (const errorLabel of errorLabels) {
                    let field = errorLabel.id.slice(0, -10);

                    if (field in response) {
                        errorLabel.innerHTML = response[field][0];
                        continue;
                    }

                    errorLabel.innerHTML = '';
                }
            }
        });
    }
</script>