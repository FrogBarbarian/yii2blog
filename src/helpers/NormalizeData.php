<?php

declare(strict_types=1);

namespace src\helpers;

/**
 * Привод в нормальный вид соответствующие данные.
 */
class NormalizeData
{
    /**
     * Приводит в нормальный вид дату (пример: 10.10.2022 = 10 октября 2022).
     * @param bool $year Нужно ли выводить год.
     */
    public static function date(string $date, bool $year = true): string
    {
        $date = explode('-', $date);
        $day = ltrim($date[2], '0');
        $month = $date[1];
        $year = $year ? $date[0] : '';
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
     * Из даты и времени формирует ответ сколько прошло времени.
     */
    public static function passedTime(string $datetime): string
    {
        $curDatetime = getdate();
        $compareDateTime = getdate(strtotime($datetime));
        $diff = [];

        foreach ($curDatetime as $key => $value) {
            $diff[$key] = (int)$value - (int)$compareDateTime[$key];
        }

        $date = strstr($datetime, ' ', true);
        $res = self::date($date);

        if ($diff['year'] === 0) {
            if ($diff['mon'] === 0) {
                if ($diff['mday'] === 1) {
                    $res = 'вчера';
                } elseif ($diff['mday'] === 0) {
                    if ($diff['hours'] > 0) {
                        $res = "{$diff['hours']} " . self::wordForm(
                                $diff['hours'],
                                'часов',
                                'час',
                                'часа'
                            ) . ' назад';
                    } elseif ($diff['hours'] === 0 && $diff['minutes'] > 0) {
                        $res = "{$diff['minutes']} " . self::wordForm(
                                $diff['minutes'],
                                'минут',
                                'минуту',
                                'минуты'
                            ) . ' назад';
                    } else {
                        $res = 'только что';
                    }
                } else {
                    $res = self::date($date, false);
                }
            }
        }

        return $res;
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
