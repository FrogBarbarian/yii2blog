<?php

declare(strict_types=1);

namespace app\components;

use yii\base\Widget;

/**
 * Фильтрация объектов в панели администратора.
 */
class AdminFilterOptionsWidget extends Widget
{
    /**
     * @var int Количество объектов на страницу.
     */
    public int $offset;
    /**
     * @var int Всего страниц.
     */
    public int $pages;
    /**
     * @var int Текущая страница.
     */
    public int $curPage;
    /**
     * @var string Текущая вкладка.
     */
    public string $tab;
    /**
     * @var string Параметр сортировки.
     */
    public string $sortParam;
    /**
     * @var int Порядок сортировки.
     */
    public int $sortOrder;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('admin-filter-options', [
            'offset' => $this->offset,
            'pages' => $this->pages,
            'curPage' => $this->curPage,
            'sortParam' => $this->sortParam,
            'sortOrder' => $this->sortOrder,
            'tab' => $this->tab,
        ]);
    }
}
