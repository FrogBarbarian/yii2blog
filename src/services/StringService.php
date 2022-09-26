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
     * @param string $ending Окончание строки (по умолчанию '...').
     * @return string Отформатированная строка.
     */
    public function cut(int $offset = 250, string $ending = '...'): string
    {
        $position = mb_strpos($this->string, ' ', $offset);
        return mb_strimwidth($this->string, 0, $position) . $ending;
    }
}
