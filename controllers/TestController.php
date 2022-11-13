<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Comment;
use app\models\Complaint;
use app\models\Post;
use app\models\Statistic;
use app\models\Tag;
use app\models\TmpPost;
use app\models\User;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\Response;

class TestController extends Controller
{
    /**
     * Добавляет случайные жалобы.
     */
    public function actionAddComplaints(string $amount = '25'): Response
    {
        for ($i = 0; $i < $amount; $i++) {
            $objectType = match (rand(0, 2)) {
                0 => 'post',
                1 => 'user',
                2 => 'comment',
            };

            $object = ('app\models\\' . ucfirst($objectType))::find()
                ->orderBy(new Expression('random()'))
                ->one();
            $sender = User::find()
                ->orderBy(new Expression('random()'))
                ->one();

            if ($sender->getIsAdmin() || $sender === $object) {
                continue;
            }

            $content = "Это жалоба от пользователя {$sender->getUsername()} на $objectType с ID {$object->getId()}";
            $complaint = new Complaint();
            $complaint
                ->setObject($objectType)
                ->setObjectId($object->getId())
                ->setSenderUsername($sender->getUsername())
                ->setComplaint($content)
                ->save();
        }

        return $this->goHome();
    }

    /**
     * Добавляет случайные комментарии.
     */
    public function actionAddComments(string $amount = '50'): Response
    {
        $amount = (int)$amount;

        for ($i = 0; $i < $amount; $i++) {
            $user = User::find()
                ->orderBy(new Expression('random()'))
                ->one();
            $post = Post::find()
                ->orderBy(new Expression('random()'))
                ->one();
            $commentContent = "Комментарий от пользователя {$user->getUsername()} для поста с ID {$post->getId()}";
            $comment = new Comment();
            $comment
                ->setPostId($post->getId())
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setComment($commentContent)
                ->save();
            $userStatistics = Statistic::find()
                ->byUsername($user->getUsername())
                ->one();
            $userStatistics
                ->increaseComments()
                ->save();
            $post
                ->increaseCommentsAmount()
                ->save();
        }

        return $this->goHome();
    }

    /**
     * Добавляет случайно сгенерированных пользователей.
     * @throws \yii\base\Exception
     */
    public function actionAddUsers(string $amount = '30'): Response
    {
        $amount = (int)$amount;

        for ($i = 0; $i < $amount; $i++) {
            $username = "User{$i}_" . rand(0, 1000);
            $user = new User();
            $user
                ->setUsername($username)
                ->setEmail("$username@ya.ru")
                ->setPassword("user$i")
                ->save();
            $statistics = new Statistic();
            $statistics
                ->setOwnerId($user->getId())
                ->setOwner($username)
                ->save();
        }

        return $this->goHome();
    }

    /**
     * Добавляет случайно сгенерированный статьи.
     */
    public function actionAddPosts(string $amount = '50'): Response
    {
        $amount = (int)$amount;
        $permittedChars = ' ,.!#$%^&*(){}[]0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permittedChars .= str_repeat($permittedChars, 10);

        for ($i = 0; $i < $amount; $i++) {
            $user = User::find()
                ->orderBy(new Expression('random()'))
                ->one();
            $title = 'Пост с названием: ' . substr(str_shuffle($permittedChars), 0, 30);
            $body = 'Текст поста: ' . substr(str_shuffle($permittedChars), 0, 300) . '.';
            $tagObjects = Tag::find()
                ->orderBy(new Expression('random()'))
                ->limit(rand(1, 4))
                ->all();
            $tags = '';

            foreach ($tagObjects as $tag) {
                $tags .= "#{$tag->getTag()}";
            }

            if (rand(0, 1) === 1) {
                $tags .= '#тег' . substr(str_shuffle($permittedChars), 0, 3);
            }

            $post = new Post();
            $post
                ->setTitle($title)
                ->setBody($body)
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setTags($tags)
                ->save();
            $statistics = Statistic::find()
                ->byUsername($post->getAuthor())
                ->one();
            $statistics
                ->increasePosts()
                ->save();

            foreach ($post->getTagsArray() as $tag) {
                $tagObj = Tag::find()
                    ->byTag($tag)
                    ->one();

                if ($tagObj === null) {
                    $tagObj = new Tag();
                    $tagObj
                        ->setTag($tag)
                        ->save();
                } else {
                    $tagObj
                        ->increaseAmountOfUse()
                        ->save();
                }
            }
        }

        return $this->goHome();
    }

    /**
     * Добавляет случайно сгенерированный статьи в хранилище новых/отредактированных постов.
     */
    public function actionAddTmpPosts(string $amount = '20'): Response
    {
        $amount = (int)$amount;
        $permittedChars = ' ,.!#$%^&*(){}[]0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $permittedChars .= str_repeat($permittedChars, 10);

        for ($i = 0; $i < $amount; $i++) {
            $user = User::find()
                ->orderBy(new Expression('random()'))
                ->one();

            if ($user->getIsAdmin()) {
                continue;
            }

            $title = 'Пост с названием: ' . substr(str_shuffle($permittedChars), 0, 30);
            $body = 'Текст поста: ' . substr(str_shuffle($permittedChars), 0, 300) . '.';
            $tagObjects = Tag::find()
                ->orderBy(new Expression('random()'))
                ->limit(rand(1, 4))
                ->all();
            $tags = '';

            foreach ($tagObjects as $tag) {
                $tags .= "#{$tag->getTag()}";
            }

            if (rand(0, 1) === 1) {
                $tags .= '#тег' . substr(str_shuffle($permittedChars), 0, 3);
            }

            $post = new TmpPost();
            $post
                ->setTitle($title)
                ->setBody($body)
                ->setAuthor($user->getUsername())
                ->setAuthorId($user->getId())
                ->setTags($tags)
                ->save();
        }

        return $this->goHome();
    }
}