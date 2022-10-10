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
$activeForm = ActiveForm::begin([
    'id' => 'comment-form',
    'options'=> [
    'style' => 'width: 100%;padding-left: 5%;padding-right: 5%;',
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
<button type="button" id="addComment" class="btn btn-dark my-1">Отправить</button>
<?php ActiveForm::end() ?>

<script>
    $(document).ready(function () {
        $('#addComment').click(function () {
            let form = $('#comment-form');
            let formData = form.serialize();
            $.ajax({
                url: '/posts/add-comment',
                cache: false,
                type: 'post',
                data: formData,
                success: function (response) {
                    if (typeof (response) === "string") {
                        document.getElementById("commentArea").value = document.getElementById("commentArea").defaultValue;
                        $('#errorLabel').html('');
                        updateComments();
                    } else {
                        $('#errorLabel').html(response['comment'][0]);
                    }
                }
            });
        });
    });
</script>