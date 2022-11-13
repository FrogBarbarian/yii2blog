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
            $color = 'rgba(100,100,100,.3)';
        }

        return "<span class='text-center' style='display: inline-block;width: 50px;color:$color'>$pre$rating</span>";
    }
}
