<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => $_ENV['DB_DSN'] ?? 'mysql:host=db;dbname=manacas_dev',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? 'root',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8'

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
