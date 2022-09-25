<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\db\Exception;

class PostInteractionsForm extends ActiveRecord
{
    /**
     * @var string Название нового поста.
     */
    public string $title = '';
    /**
     * @var string Текст нового поста.
     */
    public string $body = '';
    /**
     * @var string Таблица с временным хранением постов
     */
    private string $_postsTmp = 'posts_tmp';

    /**
     * @return string Название таблицы с постами.
     */
    public static function tableName(): string
    {
        return 'posts';
    }

    /**
     * @return array Правила валидации нового поста.
     */
    public function rules(): array
    {
        return [
            [['title', 'body'], 'trim'],
            ['title', 'required', 'message' => 'Придумайте название поста'],
            [
                'title',
                'string',
                'length' => [10, 100],
                'tooShort' => 'Название не может быть короче 10 символов',
                'tooLong' => 'Название не может быть длиннее 100 символов',
            ],
            ['body', 'required', 'message' => 'Заполните содержимое поста'],
            [
                'body',
                'string',
                'length' => [300, 10000],
                'tooShort' => 'Название не может быть короче 300 символов',
                'tooLong' => 'Название не может быть длиннее 10000 символов',
            ],
        ];
    }

    /**
     * Создает или обновляет пост. Если автор админ, то сразу производит изменения.
     * Иначе пост отправляется на проверку админу.
     * @return void
     */
//    public function initPost(): void
//    {
//        if (!isset(Yii::$app->session['admin'])) {
//            $params['isNew'] = !isset($_POST['NewPostForm']['isEdit']);
//            $params['author'] = Yii::$app->session['id'];
//            $table = $this->_postsTmp;
//            $this->createPost($table, $params);
//        } else {
//            $id = $_POST['NewPostForm']['id'];
//            $table = self::tableName();
//            if (isset($_POST['NewPostForm']['isEdit'])) {
//                $params['author'] = $_POST['NewPostForm']['author'];
//                $this->updatePost($params, $id);
//            } else {
//                $this->createPost($table, $params);
//            }
//        }
//    }


    private function initPost(): array
    {
        $params = ['title' => $this->title, 'body' => $this->body];

        if (Yii::$app->session->has('admin')) {
            $table = self::tableName();
        } else {
            if ($_POST['isNew']) {

            } else {

            }
            $params['author'] = Yii::$app->session['login'];
            $table = $this->_postsTmp;
        }

        return ['table' => $table, 'params' => $params];
    }

    private function prepareNewPost(): array
    {
        $params = [
            'title' => $this->title,
            'body' => $this->body,
            'author' => Yii::$app->session['login'],
        ];

        if (Yii::$app->session->has('admin')) {
            $table = self::tableName();
        } else {
            $table = $this->_postsTmp;
        }
        return ['table' => $table, 'params' => $params];
    }

    /**
     * Создает запись в БД с новым постом.
     * @return void
     * @throws Exception
     */
    public function createPost(): void
    {
        $data = $this->prepareNewPost();
        Yii::$app
            ->getDb()
            ->createCommand()
            ->insert($data['table'], $data['params'])
            ->execute();
    }

    /**
     * Обновляет запись в таблице с постами.
     * @param array $params Параметры.
     * @param int $id ID поста.
     * @return void
     * @throws Exception
     */
    private function updatePost(array $params, int $id): void
    {
        Yii::$app
            ->getDb()
            ->createCommand()
            ->update(self::tableName(), $params, 'id = ' . $id)
            ->execute();
    }

    /**
     * Возвращает данные последнего поста пользователя.
     * @return array|bool Результат выборки|false.
     * @throws Exception
     */
//    public function getUpdatePost(): array|bool
//    {
//        return Yii::$app
//            ->getDb()
//            ->createCommand('SELECT * FROM ' . self::tableName() . ' WHERE id = ' . $_POST['NewPostForm']['id'])
//            ->queryOne();
//    }
}
