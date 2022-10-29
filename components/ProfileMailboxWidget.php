<?php

declare(strict_types=1);

namespace app\components;

use yii\base\Widget;

/**
 * Вкладка с личными сообщения пользователя.
 */
class ProfileMailboxWidget extends Widget
{
    public ?array $messages = null;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('profile-mailbox', ['messages' => $this->messages]);
    }
}
