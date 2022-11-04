<?php

declare(strict_types=1);

/**
 * @var \app\models\ChangePasswordForm $model
 */

use \yii\widgets\ActiveForm;
use \yii\helpers\Url;

$options = [
    'errorOptions' =>
        [
            'class' => 'text-danger small',
        ],
];
?>

<div class='modal-window-back' id='modalWindow' tabindex='-1'>
    <div class='modal-window'>
        <div class='modal-window-header'>
            Изменить пароль
            <button type='button' onclick='closeModalDiv()' class='btn-close'>
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'changePasswordForm',
            'action' => Url::to('/user/change-password'),
        ]) ?>
        <?= $form
            ->field($model, 'oldPassword', $options)
            ->input('password', [
                'id' => 'oldPasswordInput',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Старый пароль',
            ])
            ->label(false) ?>
        <?= $form
            ->field($model, 'newPassword', $options)
            ->input('password', [
                'id' => 'newPasswordInput',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Новый пароль',
            ])
            ->label(false) ?>
        <?= $form
            ->field($model, 'confirmNewPassword', $options)
            ->input('password', [
                'id' => 'confirmNewPasswordInput',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Подтвердите новый пароль',
            ])
            ->label(false) ?>
        <?php ActiveForm::end() ?>
        <button type="button" id="togglePasswordButton"></button>
        <div class='modal-window-footer'>
            <button type='button' onclick='closeModalDiv()' class='btn-basic'>
                Отмена
            </button>
            <button type='button' onclick="ttt()" class='btn-basic'>
                Изменить
            </button>
        </div>
    </div>
</div>

<script>
    function ttt() {
        let form = $('#changePasswordForm')
        let formData = form.serialize()
        $.ajax({
            url: form.attr('action'),
            cache: false,
            type: 'post',
            data: formData,
            success: function (r) {
                console.log(r)
            }
        })
    }
</script>

<script>
    const passwordFields = document.querySelectorAll('[type=password]');
    const togglePasswordButton = document.getElementById('togglePasswordButton');
    togglePasswordButton.addEventListener('click', () => {
        for (const field of passwordFields) {
            let typeIsPassword = field.getAttribute('type') === 'password';
            field.setAttribute('type', typeIsPassword ? 'text' : 'password');
        }
    });
</script>
