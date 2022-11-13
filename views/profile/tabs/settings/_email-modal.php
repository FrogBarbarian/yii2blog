<?php

declare(strict_types=1);

/**
 * @var \app\models\UserForm $model
 * @var \yii\web\View $this
 */

use yii\widgets\ActiveForm;
use yii\helpers\Url;

$options = [
    'errorOptions' =>
        [
            'class' => 'text-danger small',
        ],
];

$this->registerJsFile('@js/modals/change-email-form.js');
$this->registerJs(<<<JS
    setupEmailFormJsData();
JS
);
?>
<div class='modal-window-back' id='modalWindow' tabindex='-1'>
    <div class='modal-window'>
        <div class='modal-window-header'>
            Изменить email
            <button type='button' onclick='closeModalDiv()' class='btn-close'>
            </button>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'changeEmailForm',
            'enableAjaxValidation' => true,
            'action' => Url::to('/user/change-email'),
        ]) ?>
        <?= $form
            ->field($model, 'email', $options)
            ->input('email', [
                'id' => 'emailInput',
                'class' => 'txt-input-basic',
                'autocomplete' => 'off',
                'placeholder' => 'Новый email',
            ])
            ->label(false) ?>
        <?php ActiveForm::end() ?>
        <div class='modal-window-footer'>
            <button type='button' onclick='closeModalDiv()' class='btn-basic'>
                Отмена
            </button>
            <button id="changeEmailButton" type='button' class='btn-basic'>
                Изменить
            </button>
        </div>
    </div>
</div>
