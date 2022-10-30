<?php

return [
    '' => 'posts',
    'tag/<action>' => 'posts/tag',
    'author/<action>' => 'posts/author',
    '/profile' => 'profile/profile',
    'users/<action>' => 'profile/profile',
    'profile/message/<action:\d+>' => 'profile/message',
    'admin' => 'admin',
    '/<action>' => 'posts/<action>',
    'admin/<action>' => 'admin/<action>',
    '<controller>/<action>' => '<controller>/<action>',
];
