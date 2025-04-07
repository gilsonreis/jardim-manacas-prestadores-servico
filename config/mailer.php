<?php

use yii\symfonymailer\Mailer;

return [
    'class' => Mailer::class,
    'viewPath' => '@app/mail',
    'useFileTransport' => false, // altere para false em produção
    'transport' => [
//                'dsn' => 'smtp://no-reply@simplifysoftwares.com.br:ie2jZ4E5@smtp.simplifysoftwares.com.br.com:587',
        'dsn' => sprintf(
            '%s://%s:%s@%s:%s',
            getenv('SMTP_ENCRYPTION'), // smtp, smtp+tls, smtp+ssl
            getenv('SMTP_USERNAME'),
            getenv('SMTP_PASSWORD'),
            getenv('SMTP_HOST'),
            getenv('SMTP_PORT')
        ),
    ],
];