<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Форма смены пароля.
 */
class ChangePasswordForm extends ActiveRecord
{
    /**
     * @var string Старый пароль.
     */
    public string $oldPassword = '';
    /**
     * @var string Новый пароль.
     */
    public string $newPassword = '';
    /**
     * @var string Подтверждение нового пароля.
     */
    public string $confirmNewPassword = '';

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['oldPassword', 'newPassword', 'confirmNewPassword'], 'trim'],
            ['oldPassword', 'required', 'message' => 'Введите старый пароль'],
            ['oldPassword', 'validatePassword'],
            ['newPassword', 'required', 'message' => 'Придумайте пароль'],
            ['newPassword', 'string', 'length' => [5, 30], 'tooLong' => 'Максимум 30 символов', 'tooShort' => 'Минимум 5 символов'],
            ['newPassword', 'match', 'pattern' => '/^[\w-]+$/i', 'message' => 'Используются недопустимые символы'],
            ['confirmNewPassword', 'required', 'message' => 'Подтвердите Ваш пароль'],
            ['confirmNewPassword', 'compare', 'compareAttribute' => 'newPassword', 'type' => 'string' ,'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * Проверяет старый пароль на правильность ввода.
     */
    public function validatePassword(string $attribute): void
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$user->validatePassword($this->oldPassword)) {
            $this->addError($attribute, "Пароль введен не верно");
        }
    }
}
