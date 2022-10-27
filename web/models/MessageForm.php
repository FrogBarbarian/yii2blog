<?php

declare(strict_types=1);

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Форма набора сообщения.
 */
class MessageForm extends ActiveRecord
{
    /**
     * @var string Имя получателя.
     */
    public string $recipientUsername = '';
    /**
     * @var string Тема.
     */
    public string $subject = '';
    /**
     * @var string Содержание.
     */
    public string $content = '';

    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'messages';
    }

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            ['recipientUsername', 'required', 'message' => 'Выберите отправителя'],
            ['subject', 'required', 'message' => 'Заполните тему'],
            ['content', 'required', 'message' => 'Напишите что-нибудь'],
            ['recipientUsername', 'checkUserExist', 'message' => 'Пользователя с таким именем не существует'],
        ];
    }

    /**
     * Проверка на существование пользователя.
     */
    public function checkUserExist(string $attribute)
    {
        $user = User::find()
            ->byUsername($this->recipientUsername)
            ->one();

        if ($user === null) {
            $this->addError($attribute, 'Пользователя с таким именем не существует');
        }
    }
}
