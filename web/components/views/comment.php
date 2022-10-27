<?php

declare(strict_types=1);

/**
 * @var app\models\Comment $comment
 * @var app\models\User $user
 */

use src\helpers\ConstructHtml;
use src\helpers\NormalizeData;

$timestamp = NormalizeData::passedTime($comment->getDate());
?>
<li class='comment list-group-item mb-1' id='comment<?= $comment->getId() ?>'>
    <div class='d-flex w-100 justify-content-between'>
        <h5 class='mb-1'>
            <a class='author-link' href='/users/<?= $comment->getAuthor() ?>'>
                <?= $comment->getAuthor() ?>
            </a>
        </h5>
        <small class='text-muted'>
            <?= $timestamp ?>
        </small>
    </div>
    <p class='mb-1 text-break'>
        <?= $comment->getComment() ?>
    </p>
    <?php if ($user !== null && $user->getId() !== $comment->getAuthorID()): ?>
<?php $liked = $comment->isUserAlreadyLikedComment($user->getId()) ? 'd' : '' ?>
    <div class='d-flex justify-content-between'>
        <div class='d-flex justify-content-between'>
            <button class='like-button' onclick="likeComment('<?= $comment->getId() ?>')">
                <img id='commentLike<?= $comment->getId() ?>' src='/assets/images/like<?= $liked ?>.svg' width='24'
                     alt='like'/>
            </button>
            <?php endif ?>
            <div class='comment-rating m-auto' id='commentRating<?= $comment->getId() ?>'>
                <?= ConstructHtml::rating($comment->getRating()) ?>
            </div>
            <?php if ($user !== null && $user->getId() !== $comment->getAuthorID()): ?>
            <?php $disliked = $comment->isUserAlreadyDislikedComment($user->getId()) ? 'd' : '' ?>
            <button class='like-button' onclick="dislikeComment('<?= $comment->getId() ?>')">
                <img id='commentDislike<?= $comment->getId() ?>' src='/assets/images/dislike<?= $disliked ?>.svg'
                     width='24' alt='dislike'/>
            </button>
        </div>
        <?php if (!$user->getIsAdmin()): ?>
            <button type='button'
                    onclick='createComplaint("comment", "<?= $comment->getId() ?>", "<?= $user->getId() ?>")'
                    class='btn btn-light'>
                <img src='/assets/images/create-complaint.svg' width='24' alt='create complaint'/>
            </button>
        <?php endif ?>
    </div>
<?php endif ?>
</li>