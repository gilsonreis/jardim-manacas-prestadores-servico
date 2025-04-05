<?php

Yii::setAlias('app', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
Yii::setAlias('App', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src');
Yii::setAlias('webroot', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web');

if (php_sapi_name() !== 'cli') {
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
    $url = $protocol . ($_SERVER['SERVER_PORT'] !== '80' ? ':' . $_SERVER['SERVER_PORT'] : '');

    Yii::setAlias('web', $url);
}

return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'bsVersion' => '4.x',
];
