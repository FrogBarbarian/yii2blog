<?php

declare(strict_types = 1);

namespace src\helpers;

/**
 * Сервис для конструирования пагинации на странице.
 */
class PaginationHelper
{
    /**
     * Получает страницы для пагинации.
     * Возвращает массив с номерами страниц, номера страниц не могут быть выше общего количества страниц.
     * @param int $curPage Текущая страница.
     * @param int $pages Всего страниц.
     * @param int $leftArm Максимальное количество кнопок со страницами слева от текущей (по умолчанию 3).
     * @param int $rightArm Максимальное количество кнопок со страницами справа от текущей (по умолчанию 3).
     * @return array
     */
    public function getNavPages(int $curPage, int $pages, int $leftArm = 3, int $rightArm = 3): array
    {
        $maxSize = $leftArm + $rightArm + 1;
        for ($i = $curPage - $leftArm; $i < $curPage; $i++) {
            if ($i < 1) {
                continue;
            }
            $buttons[] = $i;
            if (count($buttons) === $leftArm) {
                break;
            }
        }
        $buttons[] = $curPage;
        for ($i = $curPage + 1; $i < $curPage + $rightArm + 1; $i++) {
            if ($i === $pages + 1 || count($buttons) === $maxSize) {
                break;
            }
            $buttons[] = $i;
        }
        return $buttons;
    }
}
