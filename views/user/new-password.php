<?php

declare(strict_types=1);

/**
 * @var \app\models\UserForm $model
 * @var bool $tokenIsValid
 * @var \yii\web\View $this
 */

use yii\widgets\ActiveForm;

$this->title = 'Восстановление пароля';
$this->registerJsFile('@js/utilities/password-visibility.js');
$this->registerJsFile('@js/mini/change-password.js');
?>

<div class="modal-window window-basic">
    <div class="modal-window-header">Восстановление пароля</div>
    <div id="newPasswordWindowContent">
        <?php if ($tokenIsValid !== true): ?>
            <div class="small">
                Токен не действителен.
            </div>
        <?php else: ?>
            <?php $form = ActiveForm::begin([
                'id' => 'newPasswordForm',
                'enableAjaxValidation' => true,
            ]) ?>
            <?= $form
                ->field($model, 'password')
                ->input('password', [
                    'class' => 'txt-input-basic',
                    'placeholder' => 'Новый пароль',
                ])
                ->label(false)
                ->error(['class' => 'text-danger small help-block']) ?>
            <button type="button" class="btn-basic" id="togglePasswordButton">
                <img src="<?= IMAGES ?>password-hide.svg" alt="show password">
            </button>
            <button type="button" id="setNewPasswordButton" class="btn-basic">Изменить</button>
            <?php ActiveForm::end() ?>
        <?php endif ?>
    </div>
</div>
