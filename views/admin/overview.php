<?php

declare(strict_types=1);

/**
 * @var int $postsAmount
 * @var \app\models\Post $mostViewedPost
 * @var \app\models\Post $highestRatingPost
 * @var \app\models\Post $lowestRatingPost
 * @var \app\models\Post $mostCommentablePost
 * @var int $usersAmount
 * @var \app\models\Statistic $mostPostUser
 * @var \app\models\Statistic $mostCommentUser
 * @var \app\models\Statistic $highestRatingUser
 * @var \app\models\Statistic $lowestRatingUser
 * @var int $commentsAmount
 * @var \app\models\Comment $highestRatingComment
 * @var \app\models\Comment $lowestRatingComment
 * @var int $amountLikes
 * @var int $amountDislikes
 * @var int $amountViews
 * @var \yii\web\View $this
 */

use app\components\AdminMenuWidget;
use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;

$this->title = 'Статистика';
echo AdminMenuWidget::widget();
?>
<span class="admin-panel-header">Статистика</span>
<hr>
<h5>Статистика постов</h5>
<div class="mb-3 justify-content-around d-grid grid-repeat-10rem">
    <div class="stat-card">
        <?= "$postsAmount " . NormalizeData::wordForm(
            $postsAmount,
            'постов',
            'пост',
            'поста',
        ) ?>
    </div>
    <div class="stat-card">
        <a href="/post?id=<?= $mostViewedPost->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $mostViewedPost->getId() ?>
            </h6>
            Самый просматриваемый (<?= $mostViewedPost->getViews() ?>)
        </a>
    </div>
    <div class="stat-card">
        <a href="/post?id=<?= $mostCommentablePost->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $mostCommentablePost->getId() ?>
            </h6>
            Самый комментируемый (<?= $mostCommentablePost->getCommentsAmount() ?>)
        </a>
    </div>
    <div class="stat-card">
        <a href="/post?id=<?= $highestRatingPost->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $highestRatingPost->getId() ?>
            </h6>
            Высший рейтинг:  <?= ConstructHtml::rating($highestRatingPost->getRating()) ?>
        </a>
    </div>
    <div class="stat-card">
        <a href="/post?id=<?= $lowestRatingPost->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $lowestRatingPost->getId() ?>
            </h6>
            Низший рейтинг:  <?= ConstructHtml::rating($lowestRatingPost->getRating()) ?>
        </a>
    </div>
</div>
<hr>
<h5 class="mt-3">Статистика пользователей</h5>
<div class="mb-3 justify-content-around d-grid grid-repeat-10rem">
    <div class="stat-card">
        <?= "$usersAmount " . NormalizeData::wordForm(
            $usersAmount,
            'пользователей',
            'пользователь',
            'пользователя',
        ) ?>
    </div>
    <div class="stat-card">
        <a href="/users/<?= $mostPostUser->getOwner() ?>" target="_blank" class="author-link smaller">
            <h6>
                <?= $mostPostUser->getOwner() ?>
            </h6>
            Опубликовал больше всех постов (<?= $mostPostUser->getPosts() ?>)
        </a>
    </div>
    <div class="stat-card">
        <a href="/users/<?= $mostCommentUser->getOwner() ?>" target="_blank" class="author-link smaller">
            <h6>
                <?= $mostCommentUser->getOwner() ?>
            </h6>
            Написал больше всех комментариев (<?= $mostCommentUser->getComments() ?>)
        </a>
    </div>
    <div class="stat-card">
        <a href="/users/<?= $highestRatingUser->getOwner() ?>" target="_blank" class="author-link smaller">
            <h6>
                <?= $highestRatingUser->getOwner() ?>
            </h6>
            Высший рейтинг:  <?= ConstructHtml::rating($highestRatingUser->getRating()) ?>
        </a>
    </div>
    <div class="stat-card">
        <a href="/users/<?= $lowestRatingUser->getOwner() ?>" target="_blank" class="author-link smaller">
            <h6>
                <?= $lowestRatingUser->getOwner() ?>
            </h6>
            Низший рейтинг:  <?= ConstructHtml::rating($lowestRatingUser->getRating()) ?>
        </a>
    </div>
</div>
<hr>
<h5 class="mt-3">Статистика комментариев</h5>
<div class="mb-3 justify-content-around d-grid grid-repeat-10rem">
    <div class="stat-card">
        <?= "$commentsAmount " . NormalizeData::wordForm(
            $commentsAmount,
            'комментариев',
            'комментарий',
            'комментария',
        ) ?>
    </div>
    <div class="stat-card">
        <a href="/site/find-comment?id=<?= $highestRatingComment->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $highestRatingComment->getId() ?>
            </h6>
            Высший рейтинг:  <?= ConstructHtml::rating($highestRatingComment->getRating()) ?>
        </a>
    </div>
    <div class="stat-card">
        <a href="/site/find-comment?id=<?= $lowestRatingComment->getId() ?>" target="_blank" class="author-link smaller">
            <h6>
                ID: <?= $lowestRatingComment->getId() ?>
            </h6>
            Низший рейтинг:  <?= ConstructHtml::rating($lowestRatingComment->getRating()) ?>
        </a>
    </div>
</div>
<hr>
<h5>Общая статистика</h5>
<div class="mb-3 justify-content-around d-grid grid-repeat-10rem">
    <div class="stat-card">
        <?= "$amountLikes " . NormalizeData::wordForm(
            $amountLikes,
            'лайков',
            'лайк',
            'лайка',
        ) ?>
    </div>
    <div class="stat-card">
        <?= "$amountDislikes " . NormalizeData::wordForm(
            $amountDislikes,
            'дизлайков',
            'дизлайк',
            'дизлайка',
        ) ?>
    </div>
    <div class="stat-card">
        <?= "$amountViews " . NormalizeData::wordForm(
            $amountViews,
            'просмотров',
            'просмотр',
            'просмотра',
        ) ?>
    </div>
</div>
