<?php

declare(strict_types=1);

namespace app\assets;

use yii\web\AssetBundle;

class MailboxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'assets/css/mailbox.css',
    ];
    public $js = [
        'assets/js/mailbox.js',
        'assets/js/message-modal.js',
    ];
}