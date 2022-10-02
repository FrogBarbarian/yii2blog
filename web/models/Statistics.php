<?php

namespace app\models;

use app\models\queries\StatisticsQuery;
use yii\db\ActiveRecord;

class Statistics extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'statistics';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): StatisticsQuery
    {
        return new StatisticsQuery(self::class);
    }

    /**
     * Логин пользователя, за которым ведется статистика.
     */
    public function setOwner(string $owner): self
    {
        $this->setAttribute('owner', $owner);

        return $this;
    }

    /**
     * Количество просмотров пользователя.
     */
    private function setViews(int $views)
    {
        $this->setAttribute('views', $views);
    }

    /**
     * Количество постов пользователя.
     */
    private function setPosts(string $posts)
    {
        $this->setAttribute('posts', $posts);
    }

    /**
     * Количество просмотров пользователя
     */
    public function getViews(): int
    {
        return $this->getAttribute('views');
    }

    /**
     * Количество постов пользователя
     */
    public function getPosts(): int
    {
        return $this->getAttribute('posts');
    }

    /**
     * Увеличивает количество просмотров в статистике на 1.
     */
    public function increaseViews(): self
    {
        $this->setViews($this->getViews() + 1);

        return $this;
    }

    /**
     * Увеличивает количество просмотров в статистике на 1.
     */
    public function increasePosts(): self
    {
        $this->setPosts($this->getPosts() + 1);

        return $this;
    }
}
