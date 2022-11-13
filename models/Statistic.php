<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\StatisticQuery;
use yii\db\ActiveRecord;

/**
 * Модель статистики.
 */
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
     * ID пользователя.
     */
    public function setOwnerId(int $ownerId): self
    {
        $this->setAttribute('owner_id', $ownerId);

        return $this;
    }

    /**
     * Количество просмотров постов.
     */
    private function setViews(int $views)
    {
        $this->setAttribute('views', $views);
    }

    /**
     * Увеличивает количество просмотров постов на $int (по умолчанию 1).
     */
    public function increaseViews(int $int = 1): self
    {
        $this->setViews($this->getViews() + $int);

        return $this;
    }

    /**
     * Уменьшает количество просмотров постов на $int (по умолчанию 1).
     */
    public function decreaseViews(int $int = 1): self
    {
        $this->setViews($this->getViews() - $int);

        return $this;
    }

    /**
     * @return int Количество просмотров постов.
     */
    public function getViews(): int
    {
        return $this->getAttribute('views');
    }

    /**
     * Количество комментариев.
     */
    private function setComments(int $comments)
    {
        $this->setAttribute('comments', $comments);
    }

    /**
     * Увеличивает количество комментариев на $int (по умолчанию 1).
     */
    public function increaseComments(int $int = 1): self
    {
        $this->setComments($this->getComments() + $int);

        return $this;
    }

    /**
     * Уменьшает количество комментариев на $int (по умолчанию 1).
     */
    public function decreaseComments(int $int = 1): self
    {
        $this->setComments($this->getComments() - $int);

        return $this;
    }

    /**
     * @return int Количество комментариев.
     */
    public function getComments(): int
    {
        return $this->getAttribute('comments');
    }

    /**
     * Количество постов.
     */
    private function setPosts(int $posts)
    {
        $this->setAttribute('posts', $posts);
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
     * @return int Количество постов
     */
    public function getPosts(): int
    {
        return $this->getAttribute('posts');
    }

    /**
     * Количество лайков пользователю.
     */
    public function setLikes(int $likes)
    {
        $this->setAttribute('likes', $likes);
    }

    /**
     * @return int Количество лайков пользователю.
     */
    public function getLikes(): int
    {
        return $this->getAttribute('likes');
    }

    /**
     * Количество дизлайков пользователю.
     */
    public function setDislikes(int $dislikes)
    {
        $this->setAttribute('dislikes', $dislikes);
    }

    /**
     * @return int Количество дизлайков пользователю.
     */
    public function getDislikes(): int
    {
        return $this->getAttribute('dislikes');
    }

    /**
     * Обновляет рейтинг на основе общего количества лайков и дизлайков.
     */
    public function updateRating(): self
    {
        $this->setAttribute('rating',$this->getLikes() - $this->getDislikes());

        return $this;
    }

    /**
     * @return int Рейтинг пользователя.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }

        /**
     * Увеличивает количество лайков на $int (по умолчанию 1).
     */
    public function increaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() + $int);

        return $this;
    }


    /**
     * Уменьшает количество лайков на $int (по умолчанию 1).
     */
    public function decreaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() - $int);

        return $this;
    }


    /**
     * Увеличивает количество дизлайков на $int (по умолчанию 1).
     */
    public function increaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() + $int);

        return $this;
    }


    /**
     * Уменьшает количество дизлайков на $int (по умолчанию 1).
     */
    public function decreaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() - $int);

        return $this;
    }
}
