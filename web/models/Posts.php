<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Exception;

class Posts extends ActiveRecord
{
    /**
     * @return string Имя таблицы с которой работает модель.
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * Получает все посты отсортированные в обратном порядке по ID.
     * @return array
     * @throws Exception
     */
    public function getPosts(): array
    {
        return Yii::$app
            ->getDb()
            ->createCommand('SELECT * FROM ' . self::tableName() . ' ORDER BY id DESC')
            ->queryAll();
    }

    /**
     * Обрезает текст для отображения в карточке с постом.
     * @param string $text Текст поста.
     * @param int $offset Минимальная длина текста (по умолчанию 250).
     * @return string Обрезанный текст + ... на конце.
     */
    public function cutPreviewText(string $text, int $offset = 250): string
    {
        $position = mb_strpos($text, ' ', $offset);
        return mb_strimwidth($text, 0, $position) . '...';
    }
}
