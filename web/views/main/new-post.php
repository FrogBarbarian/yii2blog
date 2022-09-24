<?php
/** @var \app\models\NewPostForm $model */

use yii\widgets\ActiveForm;

$this->title = 'Новый пост';
$options = [
    'options' => ['class' => 'form-floating mb-2'],
    'errorOptions' => ['class' => 'text-danger small'],
    'template' => "{input}\n{label}\n{error}",
];
?>

<div class="rounded-5" style="background-color: #84a2a6;margin-left: 1vh;margin-right: 1vh;max-height: 90vh">
    <div class="mx-3 py-2">
        <div class="card mb-3 mx-auto rounded-4" style="border-color: #656560;border-width: medium;">
            <?php $activeForm = ActiveForm::begin([
                'id' => 'new-post-form',
            ]) ?>
            <div class="card-body">
                <?= $activeForm->field($model, 'title', $options)
                    ->input('text', [
                        'class' => 'form-control placeholder-wave',
                        'id' => 'titleInput',
                        'placeholder' => 'title',
                        'style' => 'background-color: #899aa2;',
                    ])->label('Название', ['class' => false]) ?>
                <?= $activeForm->field($model, 'body', $options)
                    ->textarea([
                        'class' => 'form-control placeholder-wave',
                        'id' => 'bodyInput',
                        'placeholder' => 'body',
                        'style' => 'background-color: #899aa2;min-height: 50vh;',
                    ])->label('Текст статьи', ['class' => false]) ?>
            </div>
            <div class="card-footer">
                <div>
                    <input type="submit" class="btn btn-outline-dark" value="Опубликовать">
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
