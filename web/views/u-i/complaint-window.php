<?php
/**
 * @var \app\models\ComplaintForm $complaintForm
 * @var string $objectType
 * @var string $objectId
 * @var string $subjectId
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<style>body {overflow: hidden;}</style>
<div style='position: fixed;left: 0;top: 0;width: 100%;height: 100%;background: #000;opacity: .6;z-index: 10000'></div>
<div class='modal fade show' id='complaintWindow' tabindex='-1' aria-modal='true' style='display: block;z-index: 10001'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content rounded-0' style='border-color: black'>
            <div class='modal-header'>
                <h1 class='modal-title fs-5'>Отправить жалобу</h1>
                <button type='button' onclick='closeComplaintWindow()' class='btn-close'  aria-label = 'Close' ></button>
            </div>
                <?php
                $options = [
                'options' => ['class' => 'form-floating'],
                'errorOptions' => ['class' => 'text-danger small', 'id' => 'errorLabel'],
                'template' => "{input}\n{label}\n{error}",
                ] ?>
                <?php $activeForm = ActiveForm::begin(['id' => 'complaint-form',
                    'options' => [
                        'style' => 'width: 100%;padding-left: 5%;padding-right: 5%;',
                    ],
                    'enableAjaxValidation' => true,
                    'validateOnType' => true,
                    'action' => Url::to('/u-i/send-complaint'),
                    'validationUrl' => Url::to('/u-i/send-complaint'),
                ]) ?>
            <div class='modal-body'>
                <?= $activeForm->field($complaintForm, 'complaint', $options)->textarea([
                'placeholder' => 'complaint',
                'id' => 'commentArea',
                'style' => 'min-height: 150px',
                ])
                ->label('Жалоба', ['class' => false]) ?>
                <?= $activeForm
                    ->field($complaintForm, 'objectType')
                    ->hiddenInput(['value' => $objectType]) ?>
                <?= $activeForm
                    ->field($complaintForm, 'objectId')
                    ->hiddenInput(['value' => $objectId]) ?>
                <?= $activeForm
                    ->field($complaintForm, 'subjectId')
                    ->hiddenInput(['value' => $subjectId]) ?>
            </div>
            <?php ActiveForm::end() ?>
            <div class='modal-footer'>
                <button type='button' onclick='closeComplaintWindow()' class='btn' >
                    Отмена
                </button>
                <button type='button' onclick="sendComplaint()" class='btn'>Отправить</button>
            </div>
        </div>
    </div>
</div>

<script>
        function sendComplaint() {
            let form = $('#complaint-form');
            let formData = form.serialize();
            $.ajax({
                url: '/u-i/send-complaint',
                cache: false,
                type: 'post',
                data: formData,
                success: function (response) {
                    if (response === true) {
                        closeComplaintWindow();
                        alert('Жалоба успешно отправлена.');
                    } else {
                        $('#errorLabel').html(response['complaint'][0]);
                    }
                }
            });
        }
</script>