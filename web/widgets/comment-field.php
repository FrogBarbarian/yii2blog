<?php
/**
 * @var \app\models\CommentForm $commentForm
 * @var \app\models\Post $post
 */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$options = [
    'options' => ['class' => 'form-floating'],
    'errorOptions' => ['class' => 'text-danger small', 'id' => 'errorLabel'],
    'template' => "{input}\n{label}\n{error}",
];
?>
<div class="rounded-2" style="background-color: white;margin-left: 5%;margin-right: 5%;margin-bottom: 1%">
    <?php $activeForm = ActiveForm::begin([
        'id' => 'comment-form',
        'options'=> [
            'style' => 'width: 100%;padding: 1%',
        ],
        'enableAjaxValidation' => true,
        'validateOnType' => true,
        'action' => Url::to('/posts/add-comment'),
        'validationUrl' => Url::to('/posts/add-comment'),
    ]) ?>
    <?= $activeForm
        ->field($commentForm, 'comment', $options)
        ->textarea([
            'placeholder' => 'comment',
            'id' => 'commentArea',
            'style' => 'min-height: 150px',
        ])
        ->label('Комментарий', ['class' => false])
    ?>
    <?= $activeForm
        ->field($commentForm, 'postId')
        ->hiddenInput(['value' => $post->getId()]) ?>
    <button type="button" id="addComment" class="btn btn-dark mt-1">Отправить</button>
    <?php ActiveForm::end() ?>
</div>
