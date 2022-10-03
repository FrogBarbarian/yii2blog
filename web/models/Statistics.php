<?php

declare(strict_types = 1);

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
     * Количество комментариев пользователя.
     */
    private function setComments(int $comments)
    {
        $this->setAttribute('comments', $comments);
    }

    /**
     * Количество постов пользователя.
     */
    private function setPosts(int $posts)
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
     * Количество комментариев пользователя
     */
    public function getComments(): int
    {
        return $this->getAttribute('comments');
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
     * Увеличивает количество постов в статистике на 1.
     */
    public function increasePosts(): self
    {
        $this->setPosts($this->getPosts() + 1);

        return $this;
    }

    /**
     * Увеличивает количество комментариев в статистике на 1.
     */
    public function increaseComments(): self
    {
        $this->setComments($this->getComments() + 1);

        return $this;
    }
}
