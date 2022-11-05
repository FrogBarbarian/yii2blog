<?php
/**
 * @var \app\models\LoginForm $loginForm
 * @var \yii\web\View $this
 */

use yii\widgets\ActiveForm;

$this->title = 'Вход';
$options = [
    'errorOptions' => ['class' => 'text-danger small'],
];
$this->registerJsFile('@js/password-visibility.js');
?>

<div class="align-items-center vstack justify-content-center vh-90">
    <div class="window-basic window-login">
        <h3 class="py-1">Вход</h3>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'loginForm',
        ]) ?>
        <?= $activeForm
            ->field($loginForm, 'email', $options)
            ->input('email', [
                'class' => 'txt-input-basic',
                'id' => 'emailInput',
                'placeholder' => 'Почта',
            ])
            ->label(false) ?>
        <?= $activeForm
            ->field($loginForm, 'password', $options)
            ->input('password', [
                'class' => 'txt-input-basic',
                'id' => 'passwordInput',
                'placeholder' => 'Пароль',
            ])
            ->label(false) ?>
        <button type="button" class="btn-basic" id="togglePasswordButton">
            <img src="<?= IMAGES ?>password-hide.svg" alt="show password">
        </button>
        <div class="hstack justify-content-between my-2">
            <?= $activeForm->field($loginForm, 'rememberMe')->checkbox([
                'class' => 'checkbox-input',
                'label' => 'Запомнить меня',
            ])
            ?>
            <!--TODO: реализовать восстановление пароля -->
            <a class="small" href="/user/restore">Забыли пароль?</a>
        </div>
        <button type="submit" class="btn-basic">Войти</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
