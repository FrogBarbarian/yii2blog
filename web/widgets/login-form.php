<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
$loginInputClass = 'form-control border border-2 rounded-2 bg-dark opacity-75 text-warning placeholder-wave';
?>
<!--TODO: все это не работает!-->

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
                    'action' => Url::to('/user/login'),
                    'options' => ['class' => 'form'],
                    'enableAjaxValidation' => true,
                    'validateOnType' => true,
                    'validationUrl' => Url::to('/user/login'),
                    ]); ?>
                    <?=$form
                        ->field($loginFormClass, 'login')
                        ->input('text',[
                            'class' => $loginInputClass,
                            'placeholder' => 'Логин',
                        ])
                        ->label(false)?>
                    <?=$form->field($loginFormClass, 'password')->passwordInput([
                            'class' => $loginInputClass . ' mt-2',
                            'placeholder' => 'Пароль',
                        ])->label(false) ?>
            </div>
            <div class="d-grid d-md-flex mb-2 justify-content-between">
                <div class="hstack">
                    <span class="ms-3 small text-opacity-75 text-warning row-cols-2">Запомнить меня</span>
                    <?=$form->field($loginFormClass, 'isRemember')->checkbox([
                        'class' => 'form-check-input ms-1',
                        'label' => '',
                    ])
                    ?>
                </div>
                <div>
<!--                   TODO: реализовать восстановление пароля -->
                    <a class="ms-3 small text-opacity-75 text-warning" href="#">Забыли пароль?</a>
                </div>
                <input type="submit" id="loginButton" class="btn btn-outline-warning btn-sm mx-3" value="Войти">
            </div>
                <?php ActiveForm::end() ?>
        </div>
    </div>
</div>

<!--<script>-->
<!--    $('#loginButton').click(function () {-->
<!--        $.ajax({-->
<!--            url: '/user/login',-->
<!--            success: function (res) {-->
<!--                alert('Success: ' + res)-->
<!--            },-->
<!--            error: function () {-->
<!--                alert('Error')-->
<!--            }-->
<!--        })-->
<!--    })-->
<!--</script>-->

