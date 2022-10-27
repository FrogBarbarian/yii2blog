<?php

declare(strict_types=1);

namespace app\components;

use app\models\Statistic;
use app\models\User;
use yii\base\Widget;

/**
 * Основная вкладка профиля.
 */
class ProfileOverviewWidget extends Widget
{
    public ?User $user = null;
    public ?User $visitor = null;
    public ?Statistic $statistics = null;
    public ?array $posts = null;
    public ?array $tmpPosts = null;
    public ?array $complaints = null;
    public bool $isOwn = true;

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('profile-overview', [
            'user' => $this->user,
            'visitor' => $this->visitor,
            'statistics' => $this->statistics,
            'posts' => $this->posts,
            'tmpPosts' => $this->tmpPosts,
            'complaints' => $this->complaints,
            'isOwn' => $this->isOwn,
            ]);
    }
}