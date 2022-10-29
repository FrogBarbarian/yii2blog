<?php
/** @var \app\models\LoginForm $loginForm */

use yii\widgets\ActiveForm;

$this->title = 'Вход';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
?>

<div class="align-items-center vstack justify-content-center" style="height: 90vh">
    <div class="col-md-6 px-2" style="background-color: white">
        <h3 class="py-1">Вход</h3>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'login-form',
        ]) ?>
        <?= $activeForm
            ->field($loginForm, 'email', $options)
            ->input('email', [
                'class' => 'form-control',
                'id' => 'emailInput',
                'placeholder' => 'email',
                'style' => 'background-color: #f7f7f7;max-height: 2.5rem',
            ])
            ->label('Почта', ['class' => false, 'style' => 'font-size: small']) ?>

        <div class="input-group">
        <?= $activeForm
            ->field($loginForm, 'password', $options)
            ->input('password', [
                'class' => 'form-control',
                'id' => 'passwordInput',
                'placeholder' => 'password',
                'autocomplete' => 'new-password',
                'style' => 'background-color: #f7f7f7;max-height: 2.5rem',
                'aria-describedby' => 'togglePassword',
            ])
            ->label('Пароль', ['class' => false, 'style' => 'font-size: small']) ?>
            <button type="button" id="togglePassword" style="max-height: 2.5rem;">
                <img src="/assets/images/password-hide.svg" alt="show password">
            </button>
        </div>
        <div class="hstack justify-content-between">
            <?= $activeForm->field($loginForm, 'rememberMe')->checkbox([
                'class' => 'form-check-input',
                'label' => 'Запомнить меня',
            ])
            ?>
            <!--                   TODO: реализовать восстановление пароля -->
            <a class="small" href="#">Забыли пароль?</a>
        </div>
        <button type="submit" class="btn my-2 btn-outline-dark ">Войти</button>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script src="../../web/assets/js/password-visibility.js"></script>
