<?php

namespace src\helpers;

use Yii;

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
            $color = 'rgba(100,100,100,.3)';
        }

        return "<span class='text-center' style='display: inline-block;width: 50px;color:$color'>$pre$rating</span>";
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
            $html .= "<li class='list-group-item mb-1'>" .
                "<div class='d-flex w-100 justify-content-between'>" .
                "<h5 class='mb-1'>" .
                "<a href='/user?id={$comment->getAuthorId()}'>{$comment->getAuthor()}</a>" .
                "</h5>" .
                "<small class='text-muted'>{$comment->getDate()}</small>" .
                "</div>" . // TODO: Функция отсчета времени (минуты до часа, часы до дня, вчера, день/месяц - если год тот же, точная дата, год другой)
                "<p class='mb-1 text-break'>{$comment->getComment()}</p>" .
                "<div class='comment-rating' id='commentRating{$comment->getId()}'>" .
                self::rating($comment->getRating()) .
                '</div>';

            if (Yii::$app->session->has('login') && Yii::$app->session['id'] !== $comment->getAuthorID()) {
                $likeColor = $comment->isUserLikeIt(Yii::$app->session['id']) ? 'green' : '#f7f7f7';
                $dislikeColor = $comment->isUserDislikeIt(Yii::$app->session['id']) ? 'red' : '#f7f7f7';
                $html .= "<small>" .
                    "<button class='rounded-circle' onclick='likeComment({$comment->getId()})' style='background-color:$likeColor'>" .
                    "<img src='/assets/images/like.svg' width='24' alt='like'/>" .
                    "</button>" .
                    "<button class='rounded-circle' onclick='dislikeComment({$comment->getId()})' style='background-color:$dislikeColor'>" .
                    "<img src='/assets/images/dislike.svg' width='24' alt='dislike'/>" .
                    "</button>" .
                    "</small>" .
                    "<button type='button' onclick='test('comment', {$comment->getId()}, " . Yii::$app->session['id'] . ")' class='btn btn-light rounded-end'>" .
                    "<img src='/assets/images/create-complaint.svg' width='24' alt='create complaint'/>" .
                    "</button>";
            }

            $html .= '</li>';
        }

        return $html;
    }
}
