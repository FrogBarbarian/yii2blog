<?php
/** @var \app\models\LoginForm $model */

use yii\widgets\ActiveForm;

$this->title = 'Вход';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
?>

<div class="align-items-center vstack justify-content-center" style="height: 90vh">

    <div class="col-md-6 rounded-4 p-4" style="background-color: rgba(185,146,69,0.84)">
        <h3 class="mb-5">Вход</h3>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'login-form',
        ]) ?>
        <?=$activeForm->field($model, 'email', $options)
            ->input('email', [
                'class' => 'form-control placeholder-wave',
                'id' => 'emailInput',
                'placeholder' => 'email',
                'style' => 'background-color: #899aa2;',
            ])->label('Почта', ['class' => false]) ?>
        <?=$activeForm->field($model, 'password', $options)
            ->input('password', [
                'class' => 'form-control placeholder-wave',
                'id' => 'passwordInput',
                'placeholder' => 'password',
                'autocomplete' => 'new-password',
                'style' => 'background-color: #899aa2;',
            ])->label('Пароль', ['class' => false]) ?>
        <a class="link-dark" id="togglePassword" style="cursor: pointer;text-decoration: none;">Показать пароль</a>
        <button type="submit" class="btn btn-lg btn-outline-dark mt-5 d-block">Войти</button>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#passwordInput');
    const confirmPassword = document.querySelector('#confirmPasswordInput');


    togglePassword.addEventListener('click', function () {
        const typeP = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', typeP);
    });
</script>
