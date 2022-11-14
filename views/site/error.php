<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p>
        Запрос не может быть обработан, попробуйте вернуться назад или обновить страницу.
    </p>
    <p>
        Если ошибка сохраниться, Вы можете сообщить нам об этом на
        <a href="mailto:<?= OWNER_EMAIL ?>">почту</a>.
    </p>
</div>
