<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => array(
                    'host'          => 'localhost',
                    'port'          => '3306',
                    'user'          => 'root',
                    'password'      => 'root',
                    'dbname'        => 'zend2_blog',
                    'charset'       => 'utf8',
                    'driverOptions' => array(
                        1002 => 'SET NAMES utf8'
                    ),
                ),
            )
        ),
        'migrations' => array(
            'migrations_table'     => 'migrations',
            'migrations_namespace' => 'Application',
            'migrations_directory' => 'data/migrations',
        ),
    )
);

