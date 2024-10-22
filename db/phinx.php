<?php
return [
  'paths' => [
    'migrations' => $_SERVER['DOCUMENT_ROOT'] . '/../db/migrations',
    'seeds' => $_SERVER['DOCUMENT_ROOT'] . '/../db/seeds'
  ],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_environment' => 'development',
    'production' => [
      'adapter' => 'mysql',
      'host' => getenv('DB_HOST') ?? 'localhost',
      'name' => getenv('DB_NAME'),
      'user' => getenv('DB_USER'),
      'pass' => getenv('DB_PASS'),
      'port' => getenv('DB_PORT') ?? '3306',
      'charset' => 'utf8mb4',
    ],
    'development' => [
      'adapter' => 'mysql',
      'host' => getenv('DB_HOST') ?? 'localhost',
      'name' => getenv('DB_NAME'),
      'user' => getenv('DB_USER'),
      'pass' => getenv('DB_PASS'),
      'port' => getenv('DB_PORT') ?? '3306',
      'charset' => 'utf8mb4',
    ],
    'testing' => [
      'adapter' => 'mysql',
      'host' => getenv('DB_HOST') ?? 'localhost',
      'name' => getenv('DB_NAME'),
      'user' => getenv('DB_USER'),
      'pass' => getenv('DB_PASS'),
      'port' => getenv('DB_PORT') ?? '3306',
      'charset' => 'utf8mb4',
    ]
  ],
  'version_order' => 'creation'
];