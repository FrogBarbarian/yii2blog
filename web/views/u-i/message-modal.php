<?php
/**
 * @var \app\models\MessageForm $messageForm
 */

declare(strict_types=1);

use yii\widgets\ActiveForm;

$errorOptions = [
    'class' => 'help-block text-danger small',
];
?>
<div id="modalWindow" class="modal-window-back" tabindex="-1">
    <div class="modal-window">
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
                'class' => 'div-input',
                'autocomplete' => 'off',
            ])
            ->label(false)
            ->error($errorOptions) ?>
        <?= $form
            ->field($messageForm, 'subject')
            ->input('text', [
                'class' => 'div-input',
                'autocomplete' => 'off',
            ])
            ->label(false)
            ->error($errorOptions) ?>
        <?= $form
            ->field($messageForm, 'content')
            ->input('text', [
                'class' => 'div-input',
                'autocomplete' => 'off',
            ])
            ->label(false)
            ->error($errorOptions) ?>
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
    function sendMessage() {
        let form = $('#messageForm');
        let formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            cache: false,
            data: formData,
            success: function (response) {
                console.log(response);
            }
        });

    }
</script>