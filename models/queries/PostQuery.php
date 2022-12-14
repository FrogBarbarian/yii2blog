<?php

declare(strict_types = 1);

namespace app\models\queries;

use src\services\StringService;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * Запросы к постам.
 */
class PostQuery extends ActiveQuery
{
    /**
     * Поиск по автору.
     */
    public function byAuthor(string $author): self
    {
        return $this->where(['author' => $author]);
    }

    /**
     *  Поиск по названию.
     */
    public function byTitle(string $title): self
    {
        return $this->where(['title' => $title]);
    }

    /**
     * Сортирует по ID в обратном порядке.
     */
    public function orderDescById(): self
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }

    /**
     * Рандомный поиск.
     */
    public function random(): self
    {
        return $this->orderBy(new Expression('random()'));
    }

    /**
     * Осуществляет поиск по слову запросу.
     * Для удобства склонения и форм слова, чем длиннее слово, тем больше отрезается с его конца.
     */
    public function postHasWords(string $words): self
    {
        $strLength = (new StringService($words))
            ->getLength();
        $position = (int)(ceil($strLength * .8));
        $words = mb_substr($words, 0, $position);

        return $this
            ->where(['ILIKE', 'title', $words])
            ->orWhere(['ILIKE', 'body', $words])
            ->orWhere(['ILIKE', 'tags', $words]);
    }

    /**
     *  Поиск по наличию тега.
     */
    public function byTag(string $tag): self
    {
        return $this->where(['ILIKE', 'tags', "#$tag"]);
    }
}
