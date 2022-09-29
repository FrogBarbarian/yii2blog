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
     * @return string|null Дата создания поста.
     */
    public function getDate(): string|null
    {
        return $this->getAttribute('date');
    }

    /**
     * @return string|null Теги поста.
     */
    public function getTags(): string|null
    {
        return $this->getAttribute('tags');
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
     * Устанавливает тэги поста в таблице.
     * @param string $tags
     * @return self
     */
    public function setTags(string $tags): self
    {
        $this->setAttribute('tags', $tags);

        return $this;
    }

    /**
     * Получает превью поста с помощью сервиса по работе со строкой.
     * @param string $string Строка.
     * @param int $offset Длина превью (по умолчанию 250).
     * @param string $needle Искомый символ для обрезания.
     * @param string $ending Окончание (по умолчанию '...').
     * @return string
     */
    public function getPreview(string $string, int $offset = 250, string $needle = ' ', string $ending = '...'): string
    {
        return (new StringService($string))
            ->cut($offset, $needle, $ending);
    }

    /**
     * Из строги тегов делает массив с тегами.
     * @param string $tags Строка с тегами.
     * @param string $separator Разделитель (по умолчанию ';').
     * @param int $limit Лимит элементов массива (по умолчанию PHP_INT_MAX).
     * @return array
     */
    public function getTagsArray(string $tags, string $separator = ';', int $limit = PHP_INT_MAX): array
    {
        return(new StringService($tags))
            ->explode($separator, $limit);
    }
}
