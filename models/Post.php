<?php

declare(strict_types = 1);

namespace app\models;

use app\models\queries\PostQuery;
use src\services\StringService;
use yii\db\ActiveRecord;

/**
 * Модель поста.
 */
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
     * Название.
     */
    public function setTitle(string $title): self
    {
        $this->setAttribute('title', (new StringService($title))->prepareToSave());

        return $this;
    }

    /**
     * @return string Название.
     */
    public function getTitle(): string
    {
        return $this->getAttribute('title');
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
     * @return string Содержание.
     */
    public function getBody(): string
    {
        return $this->getAttribute('body');
    }

    /**
     * Увеличивает количество просмотров поста на 1.
     */
    public function increasePostViews(): self
    {
        $this->setAttribute('viewed', $this->getViews() + 1);

        return $this;
    }

    /**
     * @return int Количество просмотров.
     */
    public function getViews(): int
    {
        return $this->getAttribute('viewed');
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
     * @return string Дата создания.
     */
    public function getDatetime(): string
    {
        return $this->getAttribute('datetime');
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
     * @return string Теги.
     */
    public function getTags(): string
    {
        return $this->getAttribute('tags');
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
     * @return bool Доступность комментирования.
     */
    public function getIsCommentable(): bool
    {
        return $this->getAttribute('is_commentable');
    }

    /**
     * Дополняет список ID пользователей лайкнувших пост.
     */
    public function addLikedPostByUserId(int $id): self
    {
        $this->setAttribute('users_liked', "{$this->getUsersLiked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей лайкнувших пост.
     */
    public function removeLikedPostByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersLiked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_liked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Список ID пользователей, лайкнувших пост.
     */
    private function getUsersLiked(): string
    {
        return $this->getAttribute('users_liked');
    }

    /**
     * Проверяет, есть ли юзер в списке лайкнувших пост.
     */
    public function isUserAlreadyLikedPost(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersLiked());

        return in_array($id, $usersIds);
    }

    /**
     * Дополняет список ID пользователей дизлайкнувших пост.
     */
    public function addDislikedPostByUserId(int $id): self
    {
        $this->setAttribute('users_disliked', "{$this->getUsersDisliked()}$id ");

        return $this;
    }

    /**
     * Убирает ID из списка ID пользователей дизлайкнувших пост.
     */
    public function removeDislikedPostByUserId(int $id): self
    {
        $usersIds = explode(' ', $this->getUsersDisliked());
        $usersIds = array_diff($usersIds, [$id]);
        $this->setAttribute('users_disliked', implode(' ', $usersIds));

        return $this;
    }

    /**
     * Список ID пользователей, дизлайкнувших пост.
     */
    private function getUsersDisliked(): string
    {
        return $this->getAttribute('users_disliked');
    }

    /**
     * Проверяет, есть ли юзер в списке дизлайкнувших пост.
     */
    public function isUserAlreadyDislikedPost(int $id): bool
    {
        $usersIds = explode(' ', $this->getUsersDisliked());

        return in_array($id, $usersIds);
    }

    /**
     * Увеличивает количество лайков поста на $int (по умолчанию 1).
     */
    public function increaseLikes(int $int = 1): self
    {
        $this->setAttribute('likes', $this->getLikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество лайков поста на $int (по умолчанию 1).
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
     * Увеличивает количество дизлайков поста на $int (по умолчанию 1).
     */
    public function increaseDislikes(int $int = 1): self
    {
        $this->setAttribute('dislikes',$this->getDislikes() + $int);

        return $this;
    }

    /**
     * Уменьшает количество дизлайков поста на $int (по умолчанию 1).
     */
    public function decreaseDislikes(int $int = 1): self
    {
        $this->setAttribute('dislikes',$this->getDislikes() - $int);

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
     * Обновляет рейтинг на основе общего количества лайков и дизлайков.
     */
    public function updateRating(): self
    {
        $this->setAttribute('rating', $this->getLikes() - $this->getDislikes());

        return $this;
    }

    /**
     * @return int Рейтинг.
     */
    public function getRating(): int
    {
        return $this->getAttribute('rating');
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
     * Увеличивает количество комментариев на 1.
     */
    public function increaseCommentsAmount(): self
    {
        $this->setAttribute('comments_amount', $this->getCommentsAmount() + 1);

        return $this;
    }

    /**
     * @return int Количество комментариев.
     */
    public function getCommentsAmount(): int
    {
        return $this->getAttribute('comments_amount');
    }
}
