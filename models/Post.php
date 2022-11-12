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
     * @return int ID.
     */
    public function getId(): int
    {
        return $this->getAttribute('id');
    }

    /**
     * @return string Название.
     */
    public function getTitle(): string
    {
        return $this->getAttribute('title');
    }

    /**
     * @return string Содержание.
     */
    public function getBody(): string
    {
        return $this->getAttribute('body');
    }

    /**
     * @return int Количество просмотров.
     */
    public function getViews(): int
    {
        return $this->getAttribute('viewed');
    }

    /**
     * @return string Автор.
     */
    public function getAuthor(): string
    {
        return $this->getAttribute('author');
    }

    /**
     * @return string Дата создания.
     */
    public function getDatetime(): string
    {
        return $this->getAttribute('datetime');
    }

    /**
     * @return string Теги.
     */
    public function getTags(): string
    {
        return $this->getAttribute('tags');
    }

    /**
     * @return bool Доступность комментирования.
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
     * @return int Рейтинг.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
    }

    /**
     * Название.
     */
    public function setTitle(string $title): self
    {
        $this->setAttribute('title', (new StringService($title))->prepareToSave());

        return $this;
    }

    /**
     * Содержание.
     */
    public function setBody(string $body): self
    {
        $this->setAttribute('body', (new StringService($body))->prepareToSave());

        return $this;
    }

    /**
     * Количество просмотров.
     */
    private function setViews(int $views)
    {
        $this->setAttribute('viewed', $views);
    }

    /**
     * Количество лайков.
     */
    private function setLikes(int $likes)
    {
        $this->setAttribute('likes', $likes);
    }

    /**
     * Количество дизлайков.
     */
    private function setDislikes(int $dislikes)
    {
        $this->setAttribute('dislikes', $dislikes);
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
     * @return string  Название до сохранения формы.
     */
    public function getOldTitle(): string
    {
        return $this->getOldAttribute('title');
    }

    /**
     * @return string Содержание до сохранения формы.
     */
    public function getOldBody(): string
    {
        return $this->getOldAttribute('body');
    }

    /**
     * @return string Теги до сохранения формы.
     */
    public function getOldTags(): string
    {
        return $this->getOldAttribute('tags');
    }

    /**
     * @return array Теги до сохранения формы.
     */
    public function getOldTagsArray(): array
    {
        $tags = $this->getOldTags();

        $array = (new StringService($tags))
            ->explode();
        unset($array[0]);

        return $array;
    }

    /**
     * Теги.
     */
    public function setTags(string $tags): self
    {
        $this->setAttribute('tags', $tags);

        return $this;
    }

    /**
     * Доступность комментирования.
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
     */
    public function getTagsArray(): array
    {
        $array = (new StringService($this->getTags()))
            ->explode();
        unset($array[0]);

        return $array;
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
    public function updateRating(): self
    {
        $this->setRating($this->getLikes() - $this->getDislikes());

        return $this;
    }

    /**
     * Список ID пользователей, лайкнувших пост.
     */
    public function getLikedByUsers(): string
    {
        return $this->getAttribute('users_liked');
    }

    /**
     * Список ID пользователей, дизлайкнувших пост.
     */
    public function getDislikedByUsers(): string
    {
        return $this->getAttribute('users_disliked');
    }

    /**
     * Дополняет список ID пользователей лайкнувших пост.
     */
    public function addLikedByUserId(int $id): self
    {
        $this->setAttribute('users_liked', "{$this->getLikedByUsers()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей лайкнувших пост.
     */
    public function bateLikedByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getLikedByUsers());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_liked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей дизлайкнувших пост.
     */
    public function bateDislikedByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getDislikedByUsers());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_disliked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Дополняет список ID пользователей дизлайкнувших пост.
     */
    public function addDislikedByUserId(int $id): self
    {
        $this->setAttribute('users_disliked', "{$this->getDislikedByUsers()}$id ");

        return $this;
    }

    /**
     * Проверяет, есть ли юзер в писке лайкнувших пост.
     */
    public function isUserAlreadyLikedPost(int $id): bool
    {
        $usersIds = explode(' ', $this->getLikedByUsers());

        return in_array($id, $usersIds);
    }

    /**
     * Проверяет, есть ли юзер в писке дизлайкнувших пост.
     */
    public function isUserAlreadyDislikedPost(int $id): bool
    {
        $usersIds = explode(' ', $this->getDislikedByUsers());

        return in_array($id, $usersIds);
    }

    /**
     * ID автора.
     */
    public function setAuthorId(int $authorId):self
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
     * Записывает количество комментариев.
     */
    private function setCommentsAmount(int $amount)
    {
        $this->setAttribute('comments_amount', $amount);
    }

    /**
     * @return int Количество комментариев.
     */
    public function getCommentsAmount(): int
    {
        return $this->getAttribute('comments_amount');
    }

    /**
     * Увеличивает количество комментариев на 1.
     */
    public function increaseCommentsAmount(): self
    {
        $this->setCommentsAmount($this->getCommentsAmount() + 1);

        return $this;
    }
}
