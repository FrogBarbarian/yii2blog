<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

class PostAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'assets/js/comments.js',
        'assets/js/post.js',
    ];
}