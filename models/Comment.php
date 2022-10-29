<?php

declare(strict_types=1);

namespace app\models;

use app\models\queries\CommentQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

class Comment extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'comments';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): CommentQuery
    {
        return new CommentQuery(self::class);
    }

    /**
     * Присваивает значение post_id комменту.
     */
    public function setPostId(int $postId): self
    {
        $this->setAttribute('post_id', $postId);

        return $this;
    }

    /**
     * Присваивает автора комменту.
     */
    public function setAuthor(string $author): self
    {
        $this->setAttribute('author', $author);

        return $this;
    }

    /**
     * Присваивает ID автора комменту.
     */
    public function setAuthorId(int $authorId): self
    {
        $this->setAttribute('author_id', $authorId);

        return $this;
    }

    /**
     *  Записывает комментарий пользователя.
     */
    public function setComment(string $comment): self
    {
        $this->setAttribute('comment', (new StringService($comment))->prepareToSave());

        return $this;
    }

    /**
     *  Присваивает количество лайков.
     */
    private function setLikes(string $likes): self
    {
        $this->setAttribute('likes', $likes);

        return $this;
    }

    /**
     *  Присваивает количество дизлайков.
     */
    private function setDislikes(string $dislikes): self
    {
        $this->setAttribute('dislikes', $dislikes);

        return $this;
    }

    /**
     * Увеличивает количество лайков на $int (по умолчанию 1).
     */
    public function increaseLikes(int $int = 1): self
    {
        $this->setAttribute('likes', $this->getLikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество лайков на $int (по умолчанию 1).
     */
    public function decreaseLikes(int $int = 1): self
    {
        $this->setAttribute('likes', $this->getLikes() - $int);

        return $this;
    }

    /**
     * Увеличивает количество дизлайков на $int (по умолчанию 1).
     */
    public function increaseDislikes(int $int = 1): self
    {
        $this->setAttribute('dislikes', $this->getDislikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество дизлайков на $int (по умолчанию 1).
     */
    public function decreaseDislikes(int $int = 1): self
    {
        $this->setAttribute('dislikes', $this->getDislikes() - $int);

        return $this;
    }

    /**
     * @return int ID комментария.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string Автор комментария.
     */
    public function getAuthor(): string
    {
        return $this->getAttribute('author');
    }

    /**
     * @return int ID автора комментария.
     */
    public function getAuthorId(): int
    {
        return $this->getAttribute('author_id');
    }

    /**
     * @return string Текст комментария.
     */
    public function getComment(): string
    {
        return $this->getAttribute('comment');
    }

    /**
     * @return string Дата написания комментария.
     */
    public function getDate(): string
    {
        return $this->getAttribute('datetime');
    }

    /**
     * @return int ID поста.
     */
    public function getPostId(): int
    {
        return $this->getAttribute('post_id');
    }

    /**
     * @return int Количество лайков.
     */
    public function getLikes(): int
    {
        return $this->getAttribute('likes');
    }

    /**
     * @return int Количество дизлайков.
     */
    public function getDislikes(): int
    {
        return $this->getAttribute('dislikes');
    }

    /**
     * Присваивает рейтинг комментарию.
     */
    private function setRating(int $rating)
    {
        $this->setAttribute('rating', $rating);
    }

    /**
     * @return int Рейтинг комментария.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }

    /**
     * Список ID пользователей, лайкнувших комментарий.
     */
    private function getUsersLiked(): string
    {
        return $this->getAttribute('users_liked');
    }

    /**
     * Список ID пользователей, дизлайкнувших комментарий.
     */
    private function getUsersDisliked(): string
    {
        return $this->getAttribute('users_disliked');
    }

    /**
     * Проверяет, есть ли юзер в писке лайкнувших комментарий.
     */
    public function isUserAlreadyLikedComment(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersLiked());

        return in_array($id, $usersIds);
    }

    /**
     * Проверяет, есть ли юзер в писке дизлайкнувших комментарий.
     */
    public function isUserAlreadyDislikedComment(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersDisliked());

        return in_array($id, $usersIds);
    }

    /**
     * Дополняет список ID пользователей лайкнувших комментарий.
     */
    public function addLikedByUserId(int $id): self
    {
        $this->setAttribute('users_liked', "{$this->getUsersLiked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей лайкнувших комментарий.
     */
    public function removeLikedByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersLiked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_liked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Дополняет список ID пользователей дизлайкнувших комментарий.
     */
    public function addDislikedByUserId(int $id): self
    {
        $this->setAttribute('users_disliked', "{$this->getUsersDisliked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей дизлайкнувших комментарий.
     */
    public function removeDislikedByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersDisliked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_disliked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Обновляет рейтинг на основе общего количества лайков и дизлайков.
     */
    public function updateRating()
    {
        $this->setRating($this->getLikes() - $this->getDislikes());
        $this->save();
    }
}
