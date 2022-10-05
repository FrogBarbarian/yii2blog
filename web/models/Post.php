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
     * @return bool Можно ли комментировать пост.
     */
    public function getIsCommentable(): bool
    {
        return $this->getAttribute('is_commentable');
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
     * @return int Рейтинг поста.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }

    /**
     * Устанавливает имя поста в таблице.
     * @param string $title
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->setAttribute('title', (new StringService($title))->prepareToSave());

        return $this;
    }

    /**
     * Устанавливает текст поста в таблице.
     * @param string $body
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->setAttribute('body', (new StringService($body))->prepareToSave());

        return $this;
    }

    /**
     * Устанавливает количество просмотров поста в таблице.
     * @param int $views
     */
    private function setViews(int $views)
    {
        $this->setAttribute('viewed', $views);
    }

    /**
     * Устанавливает количество лайков поста.
     */
    private function setLikes(int $likes)
    {
        $this->setAttribute('likes', $likes);
    }

    /**
     * Устанавливает количество дизлайков поста.
     */
    private function setDislikes(int $dislikes)
    {
        $this->setAttribute('dislikes', $dislikes);
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
     * Устанавливает можно ли комментировать пост.
     * @param bool $isCommentable
     * @return self
     */
    public function setIsCommentable(bool $isCommentable): self
    {
        $this->setAttribute('is_commentable', $isCommentable);

        return $this;
    }

    /**
     * Получает превью поста с помощью сервиса по работе со строкой.
     * @param string $string Строка.
     * @param int $offset Длина превью (по умолчанию 250).
     * @param string $needle Искомый символ для обрезания (по умолчанию '.').
     * @param string $ending Окончание (по умолчанию '...').
     * @return string
     */
    public function getPreview(string $string, int $offset = 250, string $needle = '.', string $ending = '...'): string
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

    /**
     * Увеличивает количество просмотров поста на 1.
     */
    public function increasePostViews(): self
    {
        $this->setViews($this->getViews() + 1);

        return $this;
    }

    /**
     * Увеличивает количество лайков поста на $int (по умолчанию 1).
     */
    public function increaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество лайков поста на $int (по умолчанию 1).
     */
    public function decreaseLikes(int $int = 1): self
    {
        $this->setLikes($this->getLikes() - $int);

        return $this;
    }

    /**
     * Увеличивает количество дизлайков поста на $int (по умолчанию 1).
     */
    public function increaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество дизлайков поста на $int (по умолчанию 1).
     */
    public function decreaseDislikes(int $int = 1): self
    {
        $this->setDislikes($this->getDislikes() - $int);

        return $this;
    }

    /**
     * Устанавливает рейтинг поста.
     */
    private function setRating(int $int)
    {
        $this->setAttribute('rating', $int);
    }

    /**
     * Обновляет рейтинг на основе общего количества лайков и дизлайков.
     */
    public function updateRating()
    {
        $this->setRating($this->getLikes() - $this->getDislikes());
        $this->save();
    }

    /**
     * Список ID пользователей, лайкнувших пост.
     */
    public function getLikedByUsers(): string
    {
        return $this->getAttribute('liked_by_users');
    }

    /**
     * Список ID пользователей, дизлайкнувших пост.
     */
    public function getDislikedByUsers(): string
    {
        return $this->getAttribute('disliked_by_users');
    }

    /**
     * Дополняет список ID пользователей лайкнувших пост.
     */
    public function addLikedByUserId(string $id): self
    {
        $this->setAttribute('liked_by_users', "{$this->getLikedByUsers()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей лайкнувших пост.
     */
    public function bateLikedByUserId(string $id): self
    {
        $usersIds = explode(' ', $this->getLikedByUsers());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('liked_by_users', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей дизлайкнувших пост.
     */
    public function bateDislikedByUserId(string $id): self
    {
        $usersIds = explode(' ', $this->getDislikedByUsers());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('disliked_by_users', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Дополняет список ID пользователей дизлайкнувших пост.
     */
    public function addDislikedByUserId(string $id): self
    {
        $this->setAttribute('disliked_by_users', "{$this->getDislikedByUsers()}$id ");

        return $this;
    }

    /**
     * Проверяет, есть ли юзер в писке лайкнувших пост.
     */
    public function isUserLikeIt(string $id): bool
    {
        $usersIds = explode(' ', $this->getLikedByUsers());

        if (in_array($id, $usersIds, true)) {
            return true;
        }

        return false;
    }

    /**
     * Проверяет, есть ли юзер в писке дизлайкнувших пост.
     */
    public function isUserDislikeIt(string $id): bool
    {
        $usersIds = explode(' ', $this->getDislikedByUsers());

        if (in_array($id, $usersIds, true)) {
            return true;
        }

        return false;
    }
}
