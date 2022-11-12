<?php

declare(strict_types=1);

namespace app\components;

use src\helpers\Get;
use yii\base\Widget;

/**
 * Меню навигации в панели администратора.
 */
class AdminMenuWidget extends Widget
{
    /**
     * @var int|null Количество постов пользователей.
     */
    public ?int $amountTmpPosts = null;
    /**
     * @var int|null Количество неиспользуемых тегов.
     */
    public ?int $amountUnusedTags = null;
    /**
     * @var int|null Количество жалоб пользователей.
     */
    public ?int $amountComplaints = null;

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        parent::init();
        $this->amountTmpPosts = $this->amountTmpPosts !== null
            ? $this->amountTmpPosts
            : count(Get::data('tmp_posts'));

        if ($this->amountUnusedTags === null) {
            $tags = Get::data('tags');

            foreach ($tags as $tag) {
                if ($tag->getAmountOfUses() === 0) {
                    $this->amountUnusedTags++;
                }
            }
        }

        $this->amountComplaints = $this->amountComplaints !== null
            ? $this->amountComplaints
            : count(Get::data('complaints'));
    }

    /**
     * {@inheritDoc}
     */
    public function run(): string
    {
        return $this->render('admin-menu', [
            'amountTmpPosts' => $this->amountTmpPosts,
            'amountUnusedTags' => $this->amountUnusedTags,
            'amountComplaints' => $this->amountComplaints,
        ]);
    }
}
