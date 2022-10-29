<?php

declare(strict_types=1);

namespace app\components;

use yii\base\Widget;

/**
 * Навигация по страницам назад - вперед.
 */
class PageSwitcherWidget extends Widget
{
    /**
     * @var int Текущая страница.
     */
    public int $page;
    /**
     * @var int Всего страниц.
     */
    public int $pages;
    /**
     * @var int Объектов на странице.
     */
    public int $objectsOnPage;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('page-switcher', [
            'page' => $this->page,
            'pages' => $this->pages,
            'objectsOnPage' => $this->objectsOnPage,
        ]);
    }
}