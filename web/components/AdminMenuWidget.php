<?php

declare(strict_types = 1);

namespace app\components;

use Psr\SimpleCache\InvalidArgumentException;
use src\helpers\Get;
use yii\base\Widget;

/**
 * Меню навигации в панели админа.
 */
class AdminMenuWidget extends Widget
{
    public ?array $tmpPosts = null;

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException
     */
    public function init()
    {
        parent::init();
        $this->tmpPosts = $this->tmpPosts !== null ? $this->tmpPosts : Get::data('tmp_posts');

    }

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('admin-menu', ['tmpPosts' => $this->tmpPosts]);
    }
}