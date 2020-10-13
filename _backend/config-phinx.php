<?php
//require __DIR__ . '/config.php';
return [
    'paths' => [
      'migrations' => 'db/migrations'
    ],
    'migration_base_class' => '\Migration\Migration',
    'environments' => [
      'default_database' => 'dev',
      'dev' => [
        'adapter' => 'mysql',
        'host' => 'localhost',
        'name' => 'user',
        'user' => 'lucasfranson',
        'pass' => 'password',
        'port' => '3306'      
      ]
    ]
  ];