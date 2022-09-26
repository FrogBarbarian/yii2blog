<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\PostsQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

class Posts extends ActiveRecord
{
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * {@inheritDoc}
     */
    public static function find(): PostsQuery
    {
        return new PostsQuery(self::class);
    }

    /**
     * @return int|null ID поста.
     */
    public function getId(): int|null
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string|null Имя поста.
     */
    public function getTitle(): string|null
    {
        return $this->getAttribute('title');
    }

    /**
     * @return string|null Текст поста.
     */
    public function getBody(): string|null
    {
        return $this->getAttribute('body');
    }

    /**
     * @return int|null Количество просмотров поста.
     */
    public function getViews(): int|null
    {
        return $this->getAttribute('viewed');
    }

    /**
     * @return string|null Автора поста.
     */
    public function getAuthor(): string|null
    {
        return $this->getAttribute('author');
    }

    public function getPreview(int $offset = 250, string $ending = '...'): string
    {
        return (new StringService($this->getBody()))
            ->cut($offset, $ending);
    }
}
