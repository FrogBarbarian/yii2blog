<?php

declare(strict_types=1);

namespace app\components;

use Psr\SimpleCache\InvalidArgumentException;
use src\helpers\Get;
use yii\base\Widget;

/**
 * Меню навигации в панели админа.
 */
class AdminMenuWidget extends Widget
{
    public ?int $amountTmpPosts = null;
    public ?int $amountUnusedTags = null;
    public ?int $amountComplaints = null;

    /**
     * {@inheritDoc}
     * @throws InvalidArgumentException
     */
    public function init()
    {
        parent::init();
        $this->amountTmpPosts = $this->amountTmpPosts !== null
            ? $this->amountTmpPosts
            : count(Get::data(
                'tmp_posts',
                'id',
                SORT_DESC,
                false,
            ));

        if ($this->amountUnusedTags === null) {
            $tags = Get::data(
                'tags',
                'id',
                SORT_ASC,
                false,
            );

            foreach ($tags as $tag) {
                if ($tag->getAmountOfUses() === 0) {
                    $this->amountUnusedTags++;
                }
            }
        }

        $this->amountComplaints = $this->amountComplaints !== null
            ? $this->amountComplaints
            : count(Get::data(
                'complaints',
                'id',
                SORT_ASC,
                false,
            ));
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