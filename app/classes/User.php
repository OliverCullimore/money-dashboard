<?php

namespace App\Classes;

class User
{
  var $app;

  function __construct()
  {
    $this->app = \Slim\Slim::getInstance();
  }

  public static function create($username, $password)
  {
    global $db;

    // Check username is not already in use
    $result = self::find($username);
    if ($result->num_rows == 0) {
      // Insert new user
      $sql = "INSERT INTO users (username) VALUES ('" . $db->escape_string($username) . "')";
      $db->query($sql);
      if ($db->affected_rows > 0) {
        $userID = $db->insert_id;
        if (self::updatePassword($userID, $password)) {
          return true;
        }
      }
    } else {
      $_SESSION['errmsg'] = 'Username already exists.';
    }
    return false;
  }

  public static function get($userId)
  {
    global $db;

    $sql = "SELECT * FROM users WHERE user_id = '" . $db->escape_string($userId) . "'";
    return $db->query($sql);
  }

  public static function find($username)
  {
    global $db;

    $sql = "SELECT * FROM users WHERE username = '" . $db->escape_string($username) . "'";
    return $db->query($sql);
  }

  public static function login($username, $password)
  {
    // Check only a single user matches
    $result = self::find($username);
    if ($result->num_rows == 1) {
      $res = $result->fetch_assoc();

      // Check password against stored hash
      if (password_verify($password, $res['password'])) {
        $_SESSION['user_id'] = $res['user_id'];
        $_SESSION['username'] = $res['username'];
        return true;
      } else {
        $_SESSION['errmsg'] = 'Invalid login.';
      }
    } elseif ($result->num_rows > 1) {
      $_SESSION['errmsg'] = 'Error, please contact us.';
    } else {
      $_SESSION['errmsg'] = 'Invalid login.';
    }
    return false;
  }

  public static function logout()
  {
    session_destroy();
  }

  public static function isLoggedIn()
  {
    return (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0);
  }

  public static function updatePassword($userId, $password)
  {
    global $db;

    // Check only a single user matches
    $result = self::get($userId);
    if ($result->num_rows == 1) {
      $res = $result->fetch_assoc();

      // Hash password
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      // Update user password
      $sql = "UPDATE users SET password = '" . $db->escape_string($passwordHash) . "' WHERE user_id = '" . $db->escape_string($res['user_id']) . "'";
      $db->query($sql);
      if ($db->affected_rows > 0) {
        return true;
      }
    } else {
      $_SESSION['errmsg'] = 'Error, please contact us.';
    }
    return false;
  }
}
