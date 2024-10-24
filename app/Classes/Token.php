<?php
namespace App\Classes;

class Token
{
    var $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

    public static function store($token, $value, $expiry) {
      global $db;

      // Insert/update token
      $sql = "INSERT INTO tokens (token, value, expiry) VALUES ('" . $db->escape_string($token) . "', '" . $db->escape_string($value) . "', '" . $db->escape_string($expiry) . "')
      ON DUPLICATE KEY UPDATE value = '" . $db->escape_string($value) . "', expiry = '" . $db->escape_string($expiry) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function get($token) {
      global $db;

      // Get token if not expired
      $sql = "SELECT * FROM tokens WHERE token = '" . $db->escape_string($token) . "' AND expiry > NOW() + INTERVAL 5 MINUTE";
      $result = $db->query($sql);
      if ($result->num_rows > 0) {
        return $result->fetch_assoc();
      }
      return false;
    }

    public static function delete($token) {
      global $db;

      // Delete token
      $sql = "DELETE FROM tokens WHERE token = '" . $db->escape_string($token) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }
}