<?php

declare(strict_types=1);

/**
 * @var \app\models\UserForm $model
 * @var string $email
 * @var \yii\web\View $this
 */

use yii\widgets\ActiveForm;

$this->title = 'Восстановление пароля';
?>

<div class="modal-window window-basic">
    <div class="modal-window-header">Восстановление пароля</div>
    <div id="restoreWindowContent">
        <div class="small">
            На Вашу почту будет отправлено письмо со ссылкой для восстановления пароля.
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'sendResetTokenForm',
            'enableAjaxValidation' => true,
        ]) ?>
        <?= $form
            ->field($model, 'email')
            ->input('email', [
                'class' => 'txt-input-basic',
                'value' => $email,
                'placeholder' => 'Почта',
            ])
            ->label(false)
            ->error(['class' => 'text-danger small help-block']) ?>
        <button id="sendResetTokenButton" class="btn-basic">Отправить</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script>
    document.getElementById('sendResetTokenButton').onclick = () => {
        const form = $('#sendResetTokenForm');
        const formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            cache: false,
            data: formData,
            success: function (response) {
                if (response === true) {
                    $('#restoreWindowContent').html(
                        'Вам отправлено письмо со ссылкой для восстановления пароля.'
                    );
                }

            }
        });
    }
</script>
