<?php

declare(strict_types=1);

namespace app\components;


use yii\base\Widget;

/**
 * Меню навигации в панели админа.
 */
class AdminFilterOptionsWidget extends Widget
{
    public int $offset;
    public int $pages;
    public int $curPage;
    public string $tab;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('admin-filter-options', [
            'offset' => $this->offset,
            'pages' => $this->pages,
            'curPage' => $this->curPage,
            'tab' => $this->tab,
        ]);
    }
}