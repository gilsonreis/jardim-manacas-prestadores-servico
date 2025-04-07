<?php

use yii\symfonymailer\Mailer;

return [
    'class' => Mailer::class,
    'viewPath' => '@app/mail',
    'useFileTransport' => false, // altere para false em produção
    'transport' => [
//                'dsn' => 'smtp://no-reply@simplifysoftwares.com.br:ie2jZ4E5@smtp.simplifysoftwares.com.br.com:587',
        'dsn' => sprintf(
            '%s://%s:%s@default?verify_peer=0',
            $_ENV['SMTP_ENCRYPTION'], // smtp, smtp+tls, smtp+ssl
            $_ENV['SMTP_USERNAME'],
            $_ENV['SMTP_PASSWORD']
        ),
    ],
];