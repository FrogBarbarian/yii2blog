<?php

declare(strict_types = 1);

namespace app\components;

use yii\base\Widget;

/**
 * Поиск по статьям.
 */
class SearchWidget extends Widget
{
    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('search');
    }
}
