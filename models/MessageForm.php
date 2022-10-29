<?php

declare(strict_types=1);

namespace app\models;

use Yii;
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
            ['recipientUsername', 'checkUserExist'],
            ['recipientUsername', 'checkYourselfSending'],
        ];
    }

    /**
     * Проверка на существование пользователя.
     */
    public function checkUserExist(string $attribute)
    {
        $user = User::find()
            ->where(['LIKE', 'username', "%$this->recipientUsername", false])
            ->one();

        if ($user === null) {
            $this->addError($attribute, 'Пользователя с таким именем не существует');
        }
    }

    /**
     * Проверка на отправку самому себе.
     */
    public function checkYourselfSending(string $attribute)
    {
        $sender = Yii::$app
            ->user
            ->getIdentity();
        $user = User::find()
            ->where(['LIKE', 'username', "%$this->recipientUsername", false])
            ->one();

        if ($user->getUsername() === $sender->getUsername()) {
            $this->addError($attribute, 'Нельзя отправить сообщение самому себе');
        }
    }
}
