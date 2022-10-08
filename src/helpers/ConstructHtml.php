<?php

namespace src\helpers;

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
            $likeColor = $comment->isUserLikeIt(\Yii::$app->session['id']) ? 'green' : 'grey';
            $dislikeColor = $comment->isUserDislikeIt(\Yii::$app->session['id']) ? 'red' : 'grey';
            $html .= "<div class='list-group-item mb-1'>" .
                "<div class='d-flex w-100 justify-content-between'>" .
                "<h5 class='mb-1'><a href='/user?id={$comment->getAuthorId()}'>{$comment->getAuthor()}</a></h5>" .
                "<small class='text-muted'>{$comment->getDate()}</small></div>" . // TODO: Функция отсчета времени (минуты до часа, часы до дня, вчера, день/месяц - если год тот же, точная дата, год другой)
                "<p class='mb-1 text-break'>{$comment->getComment()}</p><div id='commentRating{$comment->getId()}'>" .
                self::rating($comment->getRating()) . '</div>' .
                (\Yii::$app->session->has('login') && \Yii::$app->session['id'] !== $comment->getAuthorID() ?
                    "<small><button onclick='likeComment({$comment->getId()})' style='background-color:$likeColor'>Like</button>" .
                    "<button onclick='dislikeComment({$comment->getId()})' style='background-color:$dislikeColor'>Dislike</button></small>"
                    : '') . '</div>';
            //TODO: Удаление комментария (комментарий остается в бд, но параметр is_deleted ставиться в true)
        }

        return $html;
    }

    /**
     *  Отрисовывает количество комментариев с правильным склонением.
     */
    public static function commentsAmount(int $comments): string
    {
        $html = $comments;

        if ($comments % 10 === 0 || $comments % 10 > 4 && $comments % 10 <= 9 || $comments > 10 && $comments < 15) {
            $html .= ' комментариев';
        } elseif ($comments % 10 === 1) {
            $html .= ' комментарий';
        } else {
            $html .= ' комментария';
        }

        return $html;
    }
}
