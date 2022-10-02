<?php
/** @var \app\models\User $user */

use yii\helpers\Url;
use yii\widgets\ActiveForm;

$activeForm = ActiveForm::begin([
    'id' => 'change-visibility-form',
    'action' => Url::to('/users/change-visibility')
]) ?>
<?php if ($user->getIsHidden()): ?>
    скрыт
    <button type="submit" name="show" class="small btn-link btn btn-sm">открыть?</button>
<?php else: ?>
    публичный
    <button type="submit" name="hide" class="small btn-link btn btn-sm">скрыть?</button>
<?php endif ?>
<input type="hidden" name="id" value="<?= $user->getId() ?>">
<?php ActiveForm::end() ?>

Настройки
