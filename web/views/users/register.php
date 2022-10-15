<?php
/** @var \app\models\RegisterForm $registerForm */

use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
?>

<div class="align-items-center vstack justify-content-center" style="height: 90vh">

    <div class="col-md-6 rounded-4 p-4" style="background-color: rgba(185,146,69,0.84);min-width: 450px">
        <h3 class="mb-5">Регистрация</h3>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'register-form',
        ]) ?>
        <?= $activeForm->field($registerForm, 'username', $options)
            ->input('text', [
                'class' => 'form-control placeholder-wave',
                'id' => 'usernameInput',
                'placeholder' => 'username',
                'data-toggle' => 'tooltip',
                'data-placement' => 'top',
                'title' => 'Имя пользователя не должно начинаться с нижнего подчеркивания, можно использовать латиницу и кириллицу, цифры и нижнее подчеркивание. От 3 до 30 символов.',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Имя пользователя', ['class' => false]) ?>
        <?= $activeForm->field($registerForm, 'email', $options)
            ->input('email', [
                'class' => 'form-control placeholder-wave',
                'id' => 'emailInput',
                'placeholder' => 'email',
                'autocomplete' => 'email',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Почта', ['class' => false]) ?>
        <div class="input-group">
            <?= $activeForm->field($registerForm, 'password', $options)
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
            <button type="button" id="togglePassword" style="max-height: 3.5rem">
                <img src="/assets/images/password-hide.svg" alt="show password">
            </button>
        </div>
        <?= $activeForm->field($registerForm, 'confirmPassword', $options)
            ->input('password', [
                'class' => 'form-control placeholder-wave',
                'id' => 'confirmPasswordInput',
                'placeholder' => 'confirm password',
                'autocomplete' => 'new-password',
                'style' => 'background-color: #899aa2;max-height: 3.5rem',
            ])->label('Подтвердите пароль', ['class' => false]) ?>
        <button type="submit" class="btn btn-lg btn-outline-dark mt-5 d-block">Зарегистрироваться</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
<script src="../../assets/js/password-visibility.js"></script>
