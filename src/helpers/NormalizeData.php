<?php

declare(strict_types = 1);

namespace src\helpers;

class NormalizeData
{
    public static function date(string $date): string
    {
        $date = explode('-', $date);
        $day = ltrim($date[2], '0');
        $month = $date[1];
        $year = $date[0];

        $monthsMap = [
            '01' => 'января',
            '02' => 'февраля',
            '03' => 'марта',
            '04' => 'апреля',
            '05' => 'мая',
            '06' => 'июня',
            '07' => 'июля',
            '08' => 'августа',
            '09' => 'сентября',
            '10' => 'октября',
            '11' => 'ноября',
            '12' => 'декабря',
        ];

        return "$day $monthsMap[$month] $year";
    }

    /**
     * Применяет правильное склонение слова в зависимости от исчисляемого.
     * @param int $digits Исчисляемое.
     * @param string $firstForm 1 форма (например - 'комментариев').
     * @param string $secondForm 2 форма (например - 'комментарий').
     * @param string $thirdForm 3 форма (например - 'комментария').
     */
    public static function wordForm(int $digits, string $firstForm, string $secondForm, string $thirdForm): string
    {
        if ($digits % 10 === 0 || $digits % 10 > 4 && $digits % 10 <= 9 || $digits > 10 && $digits < 15) {
            $word = $firstForm;
        } elseif ($digits % 10 === 1) {
            $word = $secondForm;
        } else {
            $word = $thirdForm;
        }

        return $word;
    }
}
