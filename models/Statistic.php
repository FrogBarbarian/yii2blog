<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\StatisticQuery;
use yii\db\ActiveRecord;

class Statistic extends ActiveRecord
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
    public static function find(): StatisticQuery
    {
        return new StatisticQuery(self::class);
    }

    /**
     * Имя пользователя.
     */
    public function setOwner(string $owner): self
    {
        $this->setAttribute('owner', $owner);

        return $this;
    }

    /**
     * @return string Имя пользователя.
     */
    public function getOwner(): string
    {
        return $this->getAttribute('owner');
    }

    /**
     * ID пользователя, за которым ведется статистика.
     */
    public function setOwnerId(int $ownerId): self
    {
        $this->setAttribute('owner_id', $ownerId);

        return $this;
    }

    /**
     * @return int ID пользователя, за которым ведется статистика.
     */
    public function getOwnerId(): int
    {
        return $this->getAttribute('owner_id');
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
     * Количество лайков пользователя.
     */
    public function setLikes(int $likes)
    {
        $this->setAttribute('likes', $likes);
    }

    /**
     * Количество дизлайков пользователя.
     */
    public function setDislikes(int $dislikes)
    {
        $this->setAttribute('dislikes', $dislikes);
    }

    /**
     * Рейтинг пользователя.
     */
    private function setRating(int $rating)
    {
        $this->setAttribute('rating', $rating);
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
     * Количество лайков пользователю.
     */
    public function getLikes(): int
    {
        return $this->getAttribute('likes');
    }

    /**
     * Количество дизлайков пользователю.
     */
    public function getDislikes(): int
    {
        return $this->getAttribute('dislikes');
    }


    /**
     * Рейтинг пользователя.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }


    /**
     * Количество постов пользователя
     */
    public function getPosts(): int
    {
        return $this->getAttribute('posts');
    }


    /**
     * Увеличивает количество просмотров в статистике на $int (по умолчанию 1).
     */
    public function increaseViews(int $int = 1): self
    {
        $this->setViews($this->getViews() + $int);

        return $this;
    }


    /**
     * Уменьшает количество просмотров в статистике на $int (по умолчанию 1).
     */
    public function decreaseViews(int $int = 1): self
    {
        $this->setViews($this->getViews() - $int);

        return $this;
    }

    /**
     * Увеличивает рейтинг в статистике на $int (по умолчанию 1).
     */
    public function increaseRating(int $int = 1): self
    {
        $this->setRating($this->getRating() + $int);

        return $this;
    }

    /**
     * Уменьшает рейтинг в статистике на $int (по умолчанию 1).
     */
    public function decreaseRating(int $int = 1): self
    {
        $this->setRating($this->getRating() - $int);

        return $this;
    }

        /**
     * Увеличивает количество лайков в статистике на $int (по умолчанию 1).
     */
    public function increaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() + $int);

        return $this;
    }


    /**
     * Уменьшает количество лайков в статистике на $int (по умолчанию 1).
     */
    public function decreaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() - $int);

        return $this;
    }


    /**
     * Увеличивает количество дизлайков в статистике на $int (по умолчанию 1).
     */
    public function increaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() + $int);

        return $this;
    }


    /**
     * Уменьшает количество дизлайков в статистике на $int (по умолчанию 1).
     */
    public function decreaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() - $int);

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
     * Уменьшает количество постов в статистике на 1.
     */
    public function decreasePosts(): self
    {
        $this->setPosts($this->getPosts() - 1);

        return $this;
    }

    /**
     * Увеличивает количество комментариев в статистике на $int (по умолчанию 1).
     */
    public function increaseComments(int $int = 1): self
    {
        $this->setComments($this->getComments() + $int);

        return $this;
    }

    /**
     * Уменьшает количество комментариев в статистике на $int (по умолчанию 1).
     */
    public function decreaseComments(int $int = 1): self
    {
        $this->setComments($this->getComments() - $int);

        return $this;
    }

    /**
     * Обновляет рейтинг на основе общего количества лайков и дизлайков.
     */
    public function updateRating(): self
    {
        $this->setRating($this->getLikes() - $this->getDislikes());

        return $this;
    }
}
