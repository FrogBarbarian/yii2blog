<?php

declare(strict_types=1);

namespace app\components;

use app\models\User;
use yii\base\Widget;

/**
 * Вкладка с настройками пользователя.
 */
class ProfileSettingsWidget extends Widget
{
    public ?User $user = null;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('profile-settings', ['user' => $this->user]);
    }
}