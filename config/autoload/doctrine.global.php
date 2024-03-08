<?php

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host' => 'db',
                    'user' => 'db_user',
                    'password' => 'db_password',
                    'dbname' => 'database',
                    'charset' => 'utf8',
                    'driverOptions' => [
                        1002 => 'SET NAMES utf8',
                    ],
                ],
            ],
        ],

        'migrations_configuration' => [
            'orm_default' => [
                'directory' => '/var/www/html/migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table'     => '__migrations',
            ],
        ],
    ],
];
