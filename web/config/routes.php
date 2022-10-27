<?php

return [
    '/' => 'posts/index',
    '/profile' => 'profile/profile',
    'users/<action>' => 'profile/profile',
    'admin/' => 'admin/index',
    'admin/<action>' => 'admin/<action>',
    '/<action>' => 'posts/<action>',
    'tag/<action>' => 'posts/tag',
    'author/<action>' => 'posts/author',
    '<controller>/<action>' => '<controller>/<action>',
];
