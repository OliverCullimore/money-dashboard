<?php

namespace App\Controllers;

use App\Classes\User;

class Users
{
  var $app;

  function __construct()
  {
    $this->app = \Slim\Slim::getInstance();
  }

  function login()
  {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
      if (\App\Classes\User::login($_POST['username'], $_POST['password'])) {
        // redirect to dashboard
        header('Location: /');
        exit();
      }
    } elseif (!empty($_POST)) {
      $_SESSION['errmsg'] = 'Missing required field.';
    }

    return $this->app->render('login.php', array(
      'title' => 'Login'
    ));
  }

  function register()
  {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
      if (\App\Classes\User::create($_POST['username'], $_POST['password'])) {
        // redirect to login
        header('Location: /login');
        exit();
      }
    } elseif (!empty($_POST)) {
      $_SESSION['errmsg'] = 'Missing required field.';
    }

    return $this->app->render('login.php', array(
      'title' => 'Register'
    ));
  }

  function user()
  {
    if (!empty($_POST['password'])) {
      if (\App\Classes\User::updatePassword($_SESSION['user_id'], $_POST['password'])) {
        $_SESSION['okmsg'] = 'Password updated.';
      }
    } elseif (!empty($_POST)) {
      $_SESSION['errmsg'] = 'Missing required field.';
    }

    return $this->app->render('user.php', array(
      'title' => $_SESSION['username'] ?? 'User'
    ));
  }

  function logout()
  {
    // logout
    User::logout();

    // redirect to login
    header('Location: /login');
    exit();
  }
}
