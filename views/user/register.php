<?php
/** @var \app\models\UserForm $model */

use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$errorOptions = ['class' => 'text-danger small help-block'];
$this->registerJsFile('@js/password-visibility.js');
?>

<div class="align-items-center vstack justify-content-center vh-90">

    <div class="window-basic">
        <h3>Регистрация</h3>
        <?php $form = ActiveForm::begin([
            'id' => 'registerForm',
            'enableAjaxValidation' => true,
        ]) ?>
        <?= $form->field($model, 'username')
            ->input('text', [
                'class' => 'txt-input-basic',
                'placeholder' => 'Имя пользователя',
                'title' => 'Имя пользователя не должно начинаться с нижнего подчеркивания, можно использовать латиницу и кириллицу, цифры и нижнее подчеркивание. От 3 до 30 символов.',
            ])->label(false)
            ->error($errorOptions) ?>
        <?= $form->field($model, 'email')
            ->input('email', [
                'class' => 'txt-input-basic',
                'placeholder' => 'Почта',
                'autocomplete' => 'email',
            ])->label(false)
            ->error($errorOptions) ?>
            <?= $form->field($model, 'password')
                ->input('password', [
                    'class' => 'txt-input-basic',
                    'placeholder' => 'Пароль',
                    'autocomplete' => 'new-password',
                    'title' => 'Пароль может содержать буквы латинского алфавита, цифры, - и _. От 5 до 30 символов.',
                ])->label(false)
                ->error($errorOptions) ?>
        <?= $form->field($model, 'confirmPassword')
            ->input('password', [
                'class' => 'txt-input-basic',
                'placeholder' => 'Подтвердите пароль',
                'autocomplete' => 'new-password',
            ])->label(false)
            ->error($errorOptions) ?>
        <button type="button" id="togglePasswordButton" class="btn-basic">
            <img src="<?= IMAGES ?>password-hide.svg" alt="show password">
        </button>
        <button type="submit" class="btn-basic d-inline-flex w-100 justify-content-center">
            Зарегистрироваться
        </button>
        <?php ActiveForm::end() ?>
    </div>
</div>
