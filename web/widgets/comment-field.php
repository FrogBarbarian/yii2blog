<?php
/**
 * @var \app\models\CommentForm $model
 * @var \app\models\Post $post
 */

use yii\widgets\ActiveForm;

$options = [
    'options' => ['class' => 'form-floating', 'style' => 'justify-content: start;'],
    'errorOptions' => ['class' => 'text-danger small', 'id' => 'errorLabel'],
    'template' => "{input}\n{label}\n{error}",
];
$activeForm = ActiveForm::begin([
    'id' => 'comment-form',
    'enableAjaxValidation' => true,
    'validateOnType' => true,
    'action' => \yii\helpers\Url::to('/posts/add-comment'),
    'validationUrl' => \yii\helpers\Url::to('/posts/add-comment'),
]) ?>
<?= $activeForm
    ->field($model, 'comment', $options)
    ->textarea([
        'placeholder' => 'comment',
        'id' => 'commentArea',
        'value' => $_POST['CommentForm']['comment'] ?? '',
        'style' => 'min-height: 150px',
    ])
    ->label('Комментарий', ['class' => false])
?>
<?= $activeForm
    ->field($model, 'postId')
    ->hiddenInput(['value' => $post->getId()]) ?>
<button type="button" id="addComment" class="btn">Отправить</button>
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
                        $('#comments').html(response);
                        document.getElementById("commentArea").value = document.getElementById("commentArea").defaultValue;
                        $('#errorLabel').html('');
                    } else {
                        $('#errorLabel').html(response['comment'][0]);
                    }
                }
            });
        });
    });
</script>