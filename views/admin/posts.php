<?php

declare(strict_types=1);

/**
 * @var \app\models\TmpPost[] $tmpPosts
 * @var \yii\web\View $this
 */

use app\components\AdminMenuWidget;

$this->title = 'Посты пользователей';
echo AdminMenuWidget::widget(['amountTmpPosts' => count($tmpPosts)]);
?>
<span class="admin-panel-header">Посты пользователей</span>
<hr>
<h6>
    На этой странице представлены посты пользователей, которые ожидают одобрения для публикации.
    Здесь могут быть как полностью новые так и новые редакции постов.
</h6>
<?php if ($tmpPosts): ?>
    <hr>
    <p class="admin-panel-info">
        Посты представлены от старых к новым слева направо.
    </p>
    <div class="posts-grid">
        <?php foreach ($tmpPosts as $post): ?>
            <a class="grid-post" target="_blank" href="<?= ADMIN_PANEL ?>/user-post?id=<?= $post->getId() ?>"
               title="<?= $post->getTitle() ?>">
                <?= $post->getPreview($post->getTitle(), 25, '') ?>
                <hr>
                <?= $post->getAuthor() ?>
                <hr class="my-1">
                <?= $post->getIsNew() ? 'Новый' : 'Отредактированный' ?>
            </a>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <p class="admin-panel-info">
        В данный момент нет постов от пользователей.
    </p>
<?php endif ?>
