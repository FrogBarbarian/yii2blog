<?php
/** @var \app\models\UserForm $model */

use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];

$this->registerJsFile('@js/password-visibility.js');
?>

<div class="align-items-center vstack justify-content-center vh-90">

    <div class="window-basic">
        <h3 class="mb-5">Регистрация</h3>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'register-form',
        ]) ?>
        <?= $activeForm->field($model, 'username', $options)
            ->input('text', [
                'class' => 'form-control placeholder-wave',
                'id' => 'usernameInput',
                'placeholder' => 'username',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Имя пользователя не должно начинаться с нижнего подчеркивания, можно использовать латиницу и кириллицу, цифры и нижнее подчеркивание. От 3 до 30 символов.',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Имя пользователя', ['class' => false]) ?>
        <?= $activeForm->field($model, 'email', $options)
            ->input('email', [
                'class' => 'form-control placeholder-wave',
                'id' => 'emailInput',
                'placeholder' => 'email',
                'autocomplete' => 'email',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Почта', ['class' => false]) ?>
        <div class="input-group">
            <?= $activeForm->field($model, 'password', $options)
                ->input('password', [
                    'class' => 'form-control placeholder-wave',
                    'id' => 'passwordInput',
                    'placeholder' => 'password',
                    'autocomplete' => 'new-password',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Пароль может содержать буквы латинского алфавита, цифры, - и _. От 5 до 30 символов.',
                    'style' => 'background-color: #899aa2;max-height: 3.5rem',
                    'aria-describedby' => 'togglePassword',
                ])->label('Пароль', ['class' => false]) ?>
            <button type="button" id="togglePasswordButton" style="max-height: 3.5rem">
                <img src="/assets/images/password-hide.svg" alt="show password">
            </button>
        </div>
        <?= $activeForm->field($model, 'confirmPassword', $options)
            ->input('password', [
                'class' => 'form-control placeholder-wave',
                'id' => 'confirmPasswordInput',
                'placeholder' => 'confirm password',
                'autocomplete' => 'new-password',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Подтвердите пароль', ['class' => false]) ?>
        <button type="submit" class="btn-basic d-inline-flex w-100 justify-content-center">
            Зарегистрироваться
        </button>
        <?php ActiveForm::end() ?>
    </div>
</div>
