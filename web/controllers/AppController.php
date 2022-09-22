<?php

namespace app\controllers;

use app\models\LoginForm;
use yii\web\Controller;

class AppController extends Controller
{
    public LoginForm $loginForm;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->loginForm = new LoginForm();

    }
}