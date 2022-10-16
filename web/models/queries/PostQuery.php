<?php

declare(strict_types = 1);

namespace app\models\queries;

use src\services\StringService;
use yii\db\ActiveQuery;
use yii\db\Expression;

class PostQuery extends ActiveQuery
{
    /**
     * Поиск по ID поста.
     */
    public function byId(int $id): self
    {
        return $this->where(['id' => $id]);
    }

    /**
     * Поиск по автору поста.
     */
    public function byAuthor(string $author): self
    {
        return $this->where(['author' => $author]);
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
        $position = intval(ceil($strLength * .8));
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
        $tag = "#$tag";

        return $this->where(['ILIKE', 'tags', $tag]);
    }
}
