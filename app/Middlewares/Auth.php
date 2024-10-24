<?php

namespace App\Middlewares;

class Auth extends \Slim\Middleware
{
  var $app;

  function __construct() {}

  public function call()
  {
    // Check user is logged in
    if (!\App\Classes\User::isLoggedIn() && !str_starts_with($_SERVER['REQUEST_URI'], '/login') && !str_starts_with($_SERVER['REQUEST_URI'], '/register')) {
      header('Location: /login');
      exit();
    }

    $this->next->call();
  }
}
