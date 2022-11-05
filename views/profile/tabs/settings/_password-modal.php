<?php

declare(strict_types=1);

/**
 * @var \app\models\ChangePasswordForm $model
 * @var \yii\web\View $this
 */

use \yii\widgets\ActiveForm;
use \yii\helpers\Url;

$options = [
    'errorOptions' =>
        [
            'class' => 'text-danger small',
        ],
];

$this->registerJsFile('@js/password-visibility.js');
$this->registerJsFile('@js/modals/change-password-form.js');
$this->registerJs(<<<JS
    setupPasswordFormJsData();
JS);

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
            'enableAjaxValidation' => true,
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
        <button type="button" id="togglePasswordButton" class="btn-basic">
            <img src="<?= IMAGES ?>password-hide.svg" alt="show password">
        </button>
        <div class='modal-window-footer'>
            <button type='button' onclick='closeModalDiv()' class='btn-basic'>
                Отмена
            </button>
            <button id="changePasswordButton" type='button' class='btn-basic'>
                Изменить
            </button>
        </div>
    </div>
</div>
