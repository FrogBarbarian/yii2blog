<?php

declare(strict_types = 1);

namespace src\services;

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
        $position = mb_strpos($this->string, $needle, $offset);
        return mb_strimwidth($this->string, 0, $position) . $ending;
    }

    /**
     * Делает из строки массив.
     * @param string $separator Искомый символ для разделения (по умолчанию ';').
     * @param int $limit Лимит элементов в массиве (по умолчанию PHP_INT_MAX).
     * @return array
     */
    public function explode(string $separator = ';', int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $this->string, $limit);
    }
}
