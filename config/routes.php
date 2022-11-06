<?php

return [
    '' => 'post',
    'tag/<action>' => 'post/tag',
    'author/<action>' => 'post/author',
    '/profile' => 'profile/profile',
    '/edit-post' => 'post-editor/edit',
    '/new-post' => 'post-editor/new',
    'users/<action>' => 'profile/profile',
    'profile/message/<action:\d+>' => 'profile/message',
    'admin' => 'admin',
    '/<action>' => 'post/<action>',
    'admin/<action>' => 'admin/<action>',
    '<controller>/<action>' => '<controller>/<action>',
];
