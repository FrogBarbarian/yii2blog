<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\PostQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
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
    public static function find(): PostQuery
    {
        return new PostQuery(self::class);
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

    /**
     * Устанавливает имя поста в таблице.
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->setAttribute('title', $title);

        return $this;
    }

    /**
     * Устанавливает текст поста в таблице.
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->setAttribute('body', $body);

        return $this;
    }

    /**
     * Устанавливает количество просмотров поста в таблице.
     * @param int $views
     * @return self
     */
    public function setViews(int $views): self
    {
        $this->setAttribute('viewed', $views);

        return $this;
    }

    /**
     * Устанавливает автора поста в таблице.
     * @param string $author
     * @return self
     */
    public function setAuthor(string $author): self
    {
        $this->setAttribute('author', $author);

        return $this;
    }

    /**
     * Получает превью поста с помощью сервиса по работе со строкой.
     * @param int $offset Длина превью (по умолчанию 250).
     * @param string $ending Окончание (по умолчанию '...').
     * @return string
     */
    public function getPreview(int $offset = 250, string $ending = '...'): string
    {
        return (new StringService($this->getBody()))
            ->cut($offset, $ending);
    }
}
