<?php

use yii\widgets\ActiveForm;
$loginInputClass = 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning placeholder-wave';
?>

<script>
    $(function login() {
        $('#loginB').click(function () {
            let loginData = {
                login: $('#login').val(),
                password: $('#password').val()
            };
            $.ajax({
                url: '/user/login',
                type: 'post',
                data: loginData,
                success: function (res) {
                    alert('All okay')
                },
                error: function () {
                    console.log(loginData);
                    alert('Something go wrong');
                }
            })
        })
    })
</script>

<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered justify-content-center">
        <div class="modal-content bg-black rounded-4 border-4 border-warning opacity-75" style="max-width: 400px">
            <div class="modal-header">
                <h5 class="modal-title text-warning">Вход</h5>
                <button type="button" class="btn-close bg-warning" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableAjaxValidation' => true,
                        'validationUrl' => \yii\helpers\Url::to(['/user/login']),
                    ]); ?>
                    <?=$form->field($loginFormClass, 'login')->textInput([
                            'class' => $loginInputClass,
                            'name' => 'login',
                            'id' => 'login',
                            'placeholder' => 'Логин',
                            'value' => $_POST['LoginForm']['login'] ?? '',
                    ])->label(false)?>
                    <?=$form->field($loginFormClass, 'password')->passwordInput([
                            'class' => $loginInputClass . ' mt-2',
                            'name' => 'password',
                            'id' => 'password',
                            'placeholder' => 'Пароль',
                            'value' => $_POST['LoginForm']['password'] ?? '',
                        ])->label(false) ?>
            </div>
            <div class="d-grid gap-2 d-md-flex mb-2 justify-content-between">
                <div class="hstack">
                    <span class="ms-3 small text-opacity-75 text-warning">Запомнить меня</span>
                    <?=$form->field($loginFormClass, 'isRemember')->checkbox([
                            'class' => 'form-check-input ms-2',
                            'label' => '',
                        ])?>
                </div>
                    <input type="button" class=" btn btn-outline-warning btn-sm mx-3" value="Войти" id="loginB">
            </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>