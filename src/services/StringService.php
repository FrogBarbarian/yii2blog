<?php

declare(strict_types=1);

namespace src\services;

use yii\helpers\HtmlPurifier;

/**
 * Сервис по работе со строками.
 */
class StringService
{
    /**
     * @var string Строка.
     */
    private string $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * Обрезает строку и добавляет окончание.
     * @param int $offset Длина строки (по умолчанию 250).
     * @param string $needle Искомое совпадение после $offset символов (по умолчанию ' ').
     * @param string $ending Окончание строки (по умолчанию '...').
     * @return string Отформатированная строка.
     */
    public function cut(int $offset = 250, string $needle = ' ', string $ending = '...'): string
    {
        try {
            $position = mb_strpos($this->string, $needle, $offset);
        } catch (\Error) {
            $position = mb_strlen($this->string);
            $ending = '';
        }
        $this->string = mb_strimwidth($this->string, 0, $position) . $ending;

        return HtmlPurifier::process($this->string);
    }

    /**
     * Делает из строки массив.
     * @param string $separator Искомый символ для разделения (по умолчанию '#').
     * @param int $limit Лимит элементов в массиве (по умолчанию PHP_INT_MAX).
     * @return array
     */
    public function explode(string $separator = '#', int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $this->string, $limit);
    }

    /**
     * Возвращает длину строки, предварительно убрав пробелы с двух сторон.
     * @return int Количество символов.
     */
    public function getLength(): int
    {
        return mb_strlen(trim($this->string));
    }

    /**
     * Подготавливает строку к сохранению в БД. Заменяет переводы строки на HTML тег <br>.
     */
    public function prepareToSave(): string
    {
        $this->string = preg_replace('/\r\n/', '<br>', $this->string);

        return HtmlPurifier::process($this->string);
    }
}
