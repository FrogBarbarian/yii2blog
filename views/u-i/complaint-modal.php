<?php
/**
 * @var \app\models\ComplaintForm $complaintForm
 * @var string $objectType
 * @var string $objectId
 */

declare(strict_types=1);

use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->registerJsFile(
    '@web/assets/js/complaint-form.js',
    ['position' => View::POS_HEAD],
    'complaint',
);
$this->registerJS(<<<JS
    setComplaintFormData();
JS);

?>

<div class='modal-window-back' id='modalWindow' tabindex='-1' aria-modal='true' style='display: block;z-index: 10001'>
    <div class='modal-window'>
        <div class='modal-window-header'>
            Отправить жалобу
            <button type='button' onclick='closeModalDiv()' class='btn-close'>
            </button>
        </div>
        <?php
        $options = [
            'options' => ['class' => 'form-floating'],
            'errorOptions' => ['class' => 'text-danger small', 'id' => 'complaintErrorLabel'],
            'template' => "{input}\n{label}\n{error}",
        ] ?>
        <?php $activeForm = ActiveForm::begin([
            'id' => 'complaint-form',
            'options' => [
                'style' => 'width: 100%',
            ],
            'action' => Url::to('/u-i/send-complaint'),
        ]) ?>
        <span style="font-size: small">
            Изложите суть жалобы. Избегайте размытых выражений и конкретизируйте.
            Уложитесь в 250 символов.
        </span>
        <div id="complaintInputField" class="div-input-basic" contenteditable="true"></div>
        <?= $activeForm
            ->field($complaintForm, 'complaint', $options)
            ->hiddenInput(['id' => 'complaintHiddenInput'])
            ->label(false) ?>
        <?= $activeForm
            ->field($complaintForm, 'objectType')
            ->hiddenInput(['value' => $objectType])
            ->label(false) ?>
        <?= $activeForm
            ->field($complaintForm, 'objectId')
            ->hiddenInput(['value' => $objectId])
            ->label(false) ?>
        <div class='modal-window-footer'>
            <button type='button' onclick='closeModalDiv()' class='toolbar-button me-1'
                    style="width: auto; font-weight: lighter">
                Отмена
            </button>
            <button type='button' onclick="sendComplaint()" class='toolbar-button'
                    style="width: auto; font-weight: lighter">
                Отправить
            </button>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>


