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
        $user = Yii::$app->user->getIdentity();

        foreach ($comments as $comment) {
            $timestamp = NormalizeData::passedTime($comment->getDate());
            $html .= "<li class='comment list-group-item mb-1' id='comment{$comment->getId()}'>" .
                "<div class='d-flex w-100 justify-content-between'>" .
                "<h5 class='mb-1'>" .
                "<a class='author-link' href='/user?id={$comment->getAuthorId()}'>{$comment->getAuthor()}</a>" .
                "</h5>" .
                "<small class='text-muted'>$timestamp</small>" .
                "</div>" .
                "<p class='mb-1 text-break'>{$comment->getComment()}</p>";

            if ($user !== null && $user->getId() !== $comment->getAuthorID()) {
                $liked = $comment->isUserAlreadyLikedComment($user->getId()) ? 'd' : '';
                $html .= "<div class='d-flex justify-content-between'>" .
                    "<div class='d-flex justify-content-between'>" .
                    "<button class='like-button' onclick='likeComment({$comment->getId()})'>" .
                    "<img id='commentLike{$comment->getId()}' src='/assets/images/like$liked.svg' width='24' alt='like' />" .
                    "</button>";
            }

            $html .= "<div class='comment-rating m-auto' id='commentRating{$comment->getId()}'>" .
                self::rating($comment->getRating()) .
                '</div>';

            if ($user !== null && $user->getId() !== $comment->getAuthorID()) {
                $disliked = $comment->isUserAlreadyDislikedComment($user->getId()) ? 'd' : '';
                $html .= "<button class='like-button' onclick='dislikeComment({$comment->getId()})'>" .
                    "<img id='commentDislike{$comment->getId()}' src='/assets/images/dislike$disliked.svg' width='24' alt='dislike'/>" .
                    "</button>" .
                    "</div>";

                if (!$user->getIsAdmin()) {
                    $html .= "<button type='button' onclick='createComplaint(\"comment\", {$comment->getId()}, {$user->getId()})' class='btn btn-light'>" .
                        "<img src='/assets/images/create-complaint.svg' width='24' alt='create complaint'/>" .
                        "</button>";
                }
                $html .= '</div>';
            }

            $html .= '</li>';
        }

        return $html;
    }
}
