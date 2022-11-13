<?php

declare(strict_types=1);

namespace app\models;

use app\models\queries\CommentQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

/**
 * Модель комментария.
 */
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
     * ID поста, к которому принадлежит.
     */
    public function setPostId(int $postId): self
    {
        $this->setAttribute('post_id', $postId);

        return $this;
    }

    /**
     * @return int ID поста, к которому принадлежит.
     */
    public function getPostId(): int
    {
        return $this->getAttribute('post_id');
    }

    /**
     * Автор.
     */
    public function setAuthor(string $author): self
    {
        $this->setAttribute('author', $author);

        return $this;
    }

    /**
     * @return string Автор.
     */
    public function getAuthor(): string
    {
        return $this->getAttribute('author');
    }

    /**
     * ID автора.
     */
    public function setAuthorId(int $authorId): self
    {
        $this->setAttribute('author_id', $authorId);

        return $this;
    }

    /**
     * @return int ID автора.
     */
    public function getAuthorId(): int
    {
        return $this->getAttribute('author_id');
    }

    /**
     *  Комментарий.
     */
    public function setComment(string $comment): self
    {
        $this->setAttribute('comment', (new StringService($comment))->prepareToSave());

        return $this;
    }

    /**
     * @return string Комментарий.
     */
    public function getComment(): string
    {
        return $this->getAttribute('comment');
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
     * @return int Количество лайков.
     */
    public function getLikes(): int
    {
        return $this->getAttribute('likes');
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
     * @return int Количество дизлайков.
     */
    public function getDislikes(): int
    {
        return $this->getAttribute('dislikes');
    }

    /**
     * @return int ID комментария.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string Дата написания.
     */
    public function getDate(): string
    {
        return $this->getAttribute('datetime');
    }

    /**
     * @return int Рейтинг.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }

    /**
     * Дополняет список ID пользователей лайкнувших комментарий.
     */
    public function addLikedCommentByUserId(int $id): self
    {
        $this->setAttribute('users_liked', "{$this->getUsersLiked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей лайкнувших комментарий.
     */
    public function removeLikedCommentByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersLiked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_liked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * ID пользователей, лайкнувших комментарий.
     */
    private function getUsersLiked(): string
    {
        return $this->getAttribute('users_liked');
    }

    /**
     * Проверяет, есть ли юзер в списке лайкнувших комментарий.
     */
    public function isUserAlreadyLikedComment(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersLiked());

        return in_array($id, $usersIds);
    }

    /**
     * Дополняет список ID пользователей дизлайкнувших комментарий.
     */
    public function addDislikedCommentByUserId(int $id): self
    {
        $this->setAttribute('users_disliked', "{$this->getUsersDisliked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей дизлайкнувших комментарий.
     */
    public function removeDislikedCommentByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersDisliked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_disliked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * ID пользователей, дизлайкнувших комментарий.
     */
    private function getUsersDisliked(): string
    {
        return $this->getAttribute('users_disliked');
    }

    /**
     * Проверяет, есть ли юзер в списке дизлайкнувших комментарий.
     */
    public function isUserAlreadyDislikedComment(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersDisliked());

        return in_array($id, $usersIds);
    }

    /**
     * Обновляет рейтинг.
     */
    public function updateRating(): self
    {
        $this->setAttribute('rating', $this->getLikes() - $this->getDislikes());

        return $this;
    }

    /**
     * Удален ли комментарий.
     */
    public function setIsDeleted(bool $isDeleted): self
    {
        $this->setAttribute('is_deleted', $isDeleted);

        return $this;
    }

    /**
     * @return bool Удален ли комментарий.
     */
    public function getIsDeleted(): bool
    {
        return $this->getAttribute('is_deleted');
    }
}
