<?php

return [
    'login' => 'users/login',
    'logout' => 'users/logout',
    '<controller:\w+>/<id:\d+>/view' => '<controller>/view',
    '<controller:\w+>/<id:\d+>/<action:\w+>' => '<controller>/<action>',
    '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    'profile' => 'users/profile',
    'cadastre-se' => 'auth/register',
    'esqueci-minha-senha' => 'auth/forgot-password',
];