<?php
require('../vendor/autoload.php');
require('../app/core/db.php');

ob_start();
session_start();

// Set timezone
date_default_timezone_set('Europe/London');

// Run database migrations
db_migrate();

// Init database connection
$db = db_connect();

// Init app
$app = new \Slim\Slim(array(
  'debug' => true,
  'log.enabled' => true,
  'templates.path' => '../app/views',
));

// Create monolog logger and store logger in container as singleton
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function () {
  $log = new \Monolog\Logger('logger');
  $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/' . date('Y-m-d') . '.log', \Monolog\Level::Debug));
  return $log;
});

// Register routes
require('../app/routes/routes.php');

// Auth middleware
$app->add(new \App\Middlewares\Auth());

// Clear error messages
$app->hook('slim.after.dispatch', function () {
  unset($_SESSION['errmsg']);
});

// Run app
$app->run();
