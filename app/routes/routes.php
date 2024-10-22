<?php
// Login
$app->map('/login', \App\Controllers\Users::class . ':login')->via('GET', 'POST');
$app->get('/logout', \App\Controllers\Users::class . ':logout');
$app->map('/register', \App\Controllers\Users::class . ':register')->via('GET', 'POST');

// User
$app->map('/user', \App\Controllers\Users::class . ':user')->via('GET', 'POST');

// Account
$app->get('/', \App\Controllers\Accounts::class . ':index');
$app->map('/accounts/new', \App\Controllers\Accounts::class . ':new')->via('GET', 'POST');
$app->get('/accounts/sync', \App\Controllers\Accounts::class . ':sync');
$app->map('/accounts/:id', \App\Controllers\Accounts::class . ':account')->via('GET', 'POST');

// Error
$app->error(function (\Exception $e) use ($app) {
  $app->render('error.php');
});
$app->notFound(function () use ($app) {
  $app->render('404.php');
});
