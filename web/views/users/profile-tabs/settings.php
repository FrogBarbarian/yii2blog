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

<ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Логин - <?= $user->getLogin() ?>
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Email - <?= $user->getEmail() ?>
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Пароль
        <button class="badge bg-primary rounded-pill">Изменить</button>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Публичный профиль
        <div class="form-check form-switch">
            <input class="form-check-input" <?= $user->getIsHidden() ? '' : 'checked' ?> type="checkbox" id="profileVisibility">
            <label class="form-check-label" for="profileVisibility"></label>
        </div>
    </li>
    <li class="list-group-item d-flex justify-content-between align-items-center">
        Личные сообщения
        <div class="form-check form-switch">
            <input class="form-check-input" checked type="checkbox" id="allowPM">
            <label class="form-check-label" for="allowPM"></label>
        </div>
    </li>
</ul>