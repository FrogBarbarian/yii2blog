<?php

namespace src\helpers;

use app\models\Comment;

/**
 * Конструирует HTML код.
 */
class ConstructHtml
{
    /**
     * Рейтинг.
     */
    public static function rating(int $rating): string
    {
        $pre = '';

        if ($rating > 0) {
            $color = 'green';
            $pre = '+';
        } elseif ($rating < 0) {
            $color = 'red';
        } else {
            $color = 'grey';
        }

        return "<span style='color:$color'>$pre$rating</span>";
    }

    /**
     * Отрисовывает элемент <img> с заданными параметрами.
     * @param string $name Имя файла.
     * @param string $alt Выводимый текст, если картинка не загружена.
     * @param int $width Ширина.
     * @param int $height Высота.
     * @param string $images Место хранения изображений.
     */
    public static function img(string $name, string $alt = '', int $width = 24, int $height = 24, string $images = IMAGES): string
    {
        return "<img src='$images$name.svg' alt='$alt' width='$width' height='$height'>";
    }

    /**
     * Отрисовывает любой элемент.
     * @param string $container Имя элемента.
     * @param string $params Параметры элемента.
     * @param string $body Содержимое элемента.
     */
    public static function any(string $container, string $params = '', string $body = ''): string
    {
        return "<$container $params>$body</$container>";
    }

    /**
     * Отрисовывает комментарии к посту.
     */
    public static function comments(array $comments): string
    {
        $html = '';

        foreach ($comments as $comment) {
            $html .= "<div class='list-group-item list-group-item-action mb-1'>" .
                "<div class='d-flex w-100 justify-content-between'>" .
                "<h5 class='mb-1'><a href='/user?id={$comment->getAuthorId()}'>{$comment->getAuthor()}</a></h5>" .
                "<small class='text-muted'>{$comment->getDate()}</small></div>" . // TODO: Функция отсчета времени (минуты до часа, часы до дня, вчера, день/месяц - если год тот же, точная дата, год другой)
                "<p class='mb-1 text-break'>{$comment->getComment()}</p>" .
                "<small class='text-muted'><a href='#'>Like</a><a href='#'>Dislike</a>" .
                //TODO: Удаление комментария (комментарий остается в бд, но параметр is_deleted ставиться в true)-
                "</small></div>";
        }

        return $html;
    }
}
