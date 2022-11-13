<?php

declare(strict_types=1);

/**
 * @var \app\models\LoginForm $model
 * @var \yii\web\View $this
 */

use yii\widgets\ActiveForm;

$this->title = 'Вход';
$options = [
    'errorOptions' => ['class' => 'text-danger small'],
];
$this->registerJsFile('@js/utilities/password-visibility.js');
?>

<div class="align-items-center vstack justify-content-center vh-90">
    <div class="window-basic window-login">
        <h3 class="py-1">Вход</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'loginForm',
        ]) ?>
        <?= $form
            ->field($model, 'email', $options)
            ->input('email', [
                'class' => 'txt-input-basic',
                'placeholder' => 'Почта',
            ])
            ->label(false) ?>
        <?= $form
            ->field($model, 'password', $options)
            ->input('password', [
                'class' => 'txt-input-basic',
                'placeholder' => 'Пароль',
            ])
            ->label(false) ?>
        <button type="button" class="btn-basic" id="togglePasswordButton">
            <img src="<?= IMAGES ?>password-hide.svg" alt="show password">
        </button>
        <div class="hstack justify-content-between my-2">
            <?= $form->field($model, 'rememberMe')->checkbox([
                'class' => 'checkbox-input',
                'label' => 'Запомнить меня',
            ])
            ?>
            <a class="small" href="<?= USER_PASSWORD_RESTORE_PAGE ?>">Забыли пароль?</a>
        </div>
        <button type="submit" class="btn-basic">Войти</button>
        <?php ActiveForm::end() ?>
    </div>
</div>
