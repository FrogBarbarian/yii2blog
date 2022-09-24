<?php

namespace app\controllers;

use app\models\Admin;
use app\models\LoginForm;
use app\models\NewPostForm;
use app\models\Post;
use app\models\Posts;
use app\models\Profile;
use app\models\RegisterForm;
use Yii;
use yii\db\Exception;
use yii\web\Response;

class MainController extends AppController
{
    /**
     * @return string Главная страница.
     * @throws Exception
     */
    public function actionIndex(): string
    {
        $model = new Posts();
        $posts = $model->getPosts();
        return $this->render('index', ['posts' => $posts, 'model' => $model]);
    }

    /**
     * Отображает страницу с выбранным (по ID из $_GET) постом, если поста с таким ID нет, то перенаправляет на главную.
     * @return string|void
     * @throws Exception
     */
    public function actionPost()
    {
        if (isset($_GET['id'])) {
            $model = new Post();
            $model->increasePostViews($_GET['id']);
            $post = $model->getPostById($_GET['id']);
            $user = $model->getUser();
            if ($post) {
                return $this->render('post',['model' => $model, 'post' => $post, 'user' => $user]);
            }
        }
        $this->goHome();
    }

    /**
     * Находит рандомный пост и открывает страничку с ним.
     * @return void
     * @throws Exception
     */
    public function actionRandom(): void
    {
        $model = new Post();
        $post = $model->getRandomPost();
        $this->redirect('/post?id=' . $post['id']);
    }

    /**
     * @return string Страница о владельце блога.
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Если данные из формы провалидированы, то создается запись в БД с пользователем.
     * @return Response|string Переадресация на страницу пользователя | Страница регистрации нового пользователя.
     * @throws Exception
     */
    public function actionRegister(): Response|string
    {
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->registerUser($model->getRegistryData());
            $session = Yii::$app->session;
            $session->open();
            $session['login'] = $model->email;
            $session['id'] = $model->getUser()['id'];
            return $this->redirect('/profile');
        }

        return $this->render('register', ['model' => $model]);
    }

    /**
     * Разлогинивает пользователя и отправляет на главную страницу.
     * @return Response
     */
    public function actionLogout(): Response
    {
        $session = Yii::$app->session;
        if ($session->has('login')) {
            $session->removeAll();
            $session->destroy();
            return $this->goHome();
        }
        return $this->goHome();
    }

    /**
     * Логинит пользователя, если данные для входа корректны.
     * @return Response|string Переадресация на страницу пользователя | Страница входа пользователя.
     * @throws Exception
     */
    public function actionLogin(): Response|string
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $session = Yii::$app->session;
            $session->open();
            $session['login'] = $model->email;
            $session['id'] = $model->getUser()['id'];
            if ($model->getUser()['isAdmin']) {
                $session['admin'] = true;
            }
            return $this->redirect('/profile');
        }
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Отображает профиль пользователя и позволяет взаимодействовать со своими данными и постами.
     * @return string Страница пользователя.
     * @throws Exception
     */
    public function actionProfile(): string
    {
        $model = new Profile();
        $user = $model->getUser();
        $posts = $model->getUserPosts($user['id']);
        if (Yii::$app->session->has('admin')) {
            $params['admin'] = new Admin();
            $params['tmpPosts'] = $params['admin']->getUsersTmpPosts();
            $params['users'] = $model->getUsers();
        }
        return $this->render('profile', [
            'model' => $model,
            'user' => $user,
            'posts' => $posts,
            'params' => $params ?? null,
        ]);
    }

    /**
     * Создает новый пост и публикует/отправляет админу на проверку.
     * @return Response|string Переадресация на пост|домашнюю страницу | Страница нового поста.
     * @throws Exception
     */
    public function actionNewPost(): Response|string
    {
        $model = new NewPostForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->createPost();
            if (Yii::$app->session->has('admin')) {
                return $this->redirect('/post?id=' . $model->getLastPost()['id']);
            } else {
                //TODO: Админу приходит на почту сообщение о новом посте
                return $this->goHome();
            }

        }
        return $this->render('new-post', ['model' => $model]);
    }


    public function actionEditPost()
    {
    }
}
