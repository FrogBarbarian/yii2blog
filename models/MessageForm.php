<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Форма сообщения.
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
            [['recipientUsername', 'subject', 'content'], 'trim'],
            ['recipientUsername', 'required', 'message' => 'Выберите отправителя'],
            ['subject', 'required', 'message' => 'Заполните тему'],
            ['subject', 'checkForTags'],
            ['subject', 'string', 'max' => 100, 'tooLong' => 'Тема письма должна быть не более 100 символов'],
            ['content', 'checkCanUserWrite'],
            ['content', 'required', 'message' => 'Напишите что-нибудь'],
            ['content', 'checkForTags'],
            ['recipientUsername', 'checkUserExist'],
            ['recipientUsername', 'checkYourselfSending'],
            ['recipientUsername', 'checkRecipientIsOpenMessages'],
        ];
    }

    /**
     * Проверка на наличие одних лишь тегов.
     */
    public function checkForTags(string $attribute): void
    {
        if (empty(strip_tags($this->$attribute))) {
            $this->addError($attribute, 'Введен некорректный текст');
        }
    }

    /**
     * Проверка на существование пользователя.
     */
    public function checkUserExist(string $attribute): void
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
    public function checkYourselfSending(string $attribute): void
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

    /**
     * Проверка может ли пользователь писать сообщения.
     */
    public function checkCanUserWrite(string $attribute): void
    {
        $user = Yii::$app
            ->user
            ->getIdentity();

        if (!$user->getCanWriteMessages()) {
            $this->addError($attribute, 'Вам запрещено писать сообщения');
        }
    }

    /**
     * Проверка, открыты ли сообщения у получателя.
     */
    public function checkRecipientIsOpenMessages(string $attribute): void
    {
        $sender = Yii::$app
            ->user
            ->getIdentity();
        $user = User::find()
            ->byUsername($this->recipientUsername)
            ->one();

        if (!$user->getIsMessagesOpen() && !$sender->getIsAdmin()) {
            $this->addError($attribute, 'Пользователь закрыл личные сообщения');
        }
    }
}
