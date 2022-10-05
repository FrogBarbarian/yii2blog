<?php

declare(strict_types = 1);

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
}
