<?php

declare(strict_types=1);

namespace app\components;

use app\models\User;

/**
 * Виджет управления разрешениями для пользователя администратором.
 */
class UserPermissionsAdminToolWidget extends \yii\base\Widget
{
    public User $user;
    public function run(): string
    {
        return $this->render('user-permissions-admin-tool', ['user' => $this->user]);
    }
}