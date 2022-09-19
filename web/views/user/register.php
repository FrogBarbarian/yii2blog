<?php
/** @var \app\models\RegistryForm $model */

use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$inputClass = 'd-flex border border-2 rounded-2 bg-black opacity-50 text-warning mt-2 placeholder-wave';
$inputOptions = [
    'errorOptions' => ['class' => 'text-danger small'],
];
?>

<!--TODO: прикрутить анимацию потряхивания при возникновении ошибки-->
<div class="align-items-center vstack justify-content-center"
     style="background-image: url(/images/reg-bg.jpg);height: 100vh;">
    <div class="rounded-4 p-4 bg-opacity-75 bg-dark">
        <a class="d-flex vstack mb-3 btn btn-outline-warning" href="/">Вернуться на главную</a>
        <hr style="color: #d0e0dc">
        <p>
            <span class="align-middle text-opacity-50 text-warning">Уже зарегистрированы?</span>
            <a class="btn btn-outline-warning my-1">Войти</a>
        </p>
        <?php $form = ActiveForm::begin(['id' => 'registry-form']); ?>
            <div class="vstack">
                <?=$form->field($model, 'login', $inputOptions)->textInput([
                    'class' => $inputClass,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Логин должен начинаться с буквы, можно использовать буквы латинского алфавита, цифры и _. От 3 до 20 символов.',
                    'placeholder' => 'Логин',
                    'value' => $_POST['RegistryForm']['login'] ?? '',
                    ])->label(false)
                ?>
                <?=$form->field($model, 'email', $inputOptions)->textInput([
                    'class' => $inputClass,
                    'placeholder' => 'Почта',
                    'value' => $_POST['RegistryForm']['email'] ?? '',
                ])->label(false)
                ?>
                <?=$form->field($model, 'password', $inputOptions)->passwordInput([
                    'class' => $inputClass,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Пароль может содержать буквы латинского алфавита, цифры, - и _. От 5 до 30 символов.',
                    'placeholder' => 'Пароль',
                    'value' => $_POST['RegistryForm']['password'] ?? '',
                ])->label(false)
                ?>
                <?=$form->field($model, 'retypePassword', $inputOptions)->passwordInput([
                    'class' => $inputClass,
                    'placeholder' => 'Повторите пароль',
                    'value' => $_POST['RegistryForm']['retypePassword'] ?? '',
                ])->label(false)
                ?>
                <button class="mt-5 btn btn-outline-warning">Зарегистрироваться</button>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

