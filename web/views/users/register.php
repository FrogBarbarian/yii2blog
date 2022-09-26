<?php
/** @var \app\models\RegisterForm $model */
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
$options = ['options' => ['class' => 'form-floating mb-2'],'errorOptions' => ['class' => 'text-danger small'], 'template' => "{input}\n{label}\n{error}"];
?>

<div class="align-items-center vstack justify-content-center" style="height: 90vh">

    <div class="col-md-6 rounded-4 p-4" style="background-color: rgba(185,146,69,0.84)">
        <h3 class="mb-5">Регистрация</h3>
        <?php $activeForm = ActiveForm::begin([
                'id' => 'register-form',
        ]) ?>
            <?=$activeForm->field($model, 'login', $options)
                ->input('text', [
                    'class' => 'form-control placeholder-wave',
                    'id' => 'loginInput',
                    'placeholder' => 'login',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Псевдоним должен начинаться с буквы, можно использовать буквы латинского алфавита, цифры и _. От 3 до 20 символов.',
                    'style' => 'background-color: #899aa2;',
                ])->label('Псевдоним', ['class' => false]) ?>
            <?=$activeForm->field($model, 'email', $options)
                ->input('email', [
                    'class' => 'form-control placeholder-wave',
                    'id' => 'emailInput',
                    'placeholder' => 'email',
                    'autocomplete' => 'email',
                    'style' => 'background-color: #899aa2;',
                ])->label('Почта', ['class' => false]) ?>
            <?=$activeForm->field($model, 'password', $options)
                ->input('password', [
                    'class' => 'form-control placeholder-wave',
                    'id' => 'passwordInput',
                    'placeholder' => 'password',
                    'autocomplete' => 'new-password',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top',
                    'title' => 'Пароль может содержать буквы латинского алфавита, цифры, - и _. От 5 до 30 символов.',
                    'style' => 'background-color: #899aa2;',
                ])->label('Пароль', ['class' => false]) ?>
            <?=$activeForm->field($model, 'confirmPassword', $options)
                ->input('password', [
                    'class' => 'form-control placeholder-wave',
                    'id' => 'confirmPasswordInput',
                    'placeholder' => 'confirm password',
                    'autocomplete' => 'new-password',
                    'style' => 'background-color: #899aa2;',
                ])->label('Подтвердите пароль', ['class' => false]) ?>
        <a class="link-dark" id="togglePassword" style="cursor: pointer;text-decoration: none;">Показать пароль</a>
        <button type="submit" class="btn btn-lg btn-outline-dark mt-5 d-block">Зарегистрироваться</button>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordInput');
    const confirmPassword = document.querySelector('#confirmPasswordInput');


    togglePassword.addEventListener('click', function (e) {
        const typeP = password.getAttribute('type') === 'password' ? 'text' : 'password';
        const typeCP = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', typeP);
        confirmPassword.setAttribute('type', typeCP);
    });
</script>
