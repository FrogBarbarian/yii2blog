<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/bootstrap.css',
        'assets/css/main.css',
        'assets/css/basic.css',
    ];
    public $js = [
        'assets/js/bootstrap.bundle.js',
        'assets/js/jquery.js',
        'assets/js/main.js',
        'assets/js/post-search.js',
        'assets/js/utilities/complaint-modal.js',
    ];
    public $jsOptions = [
        'position' => View::POS_HEAD,
    ];
}