<?php
/** @var \app\models\CommentForm $model */

use yii\widgets\ActiveForm;

$options = [
    'options' => ['class' => 'form-floating', 'style' => 'justify-content: start;'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
$activeForm = ActiveForm::begin(['id' => 'comment-form']) ?>
<?=$activeForm
    ->field($model, 'comment', $options)
    ->textarea([
        'placeholder' => 'comment',
        'id' => 'commentArea',
        'value' => '',
        'style' => 'min-height: 150px',
    ])
    ->label('Комментарий', ['class' => false])
?>
    <input type="submit" class="btn">
<?php ActiveForm::end() ?>