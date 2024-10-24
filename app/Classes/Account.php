<?php
namespace App\Classes;

class Account
{
    var $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

    public static function create($userId, $externalId, $name, $status) {
      global $db;

      // Insert new account
      $sql = "INSERT INTO accounts (user_id, external_id, name, status) VALUES ('" . $db->escape_string($userId) . "', '" . $db->escape_string($externalId) . "', '" . $db->escape_string($name) . "', '" . $db->escape_string($status) . "')";
      $db->query($sql);
      return ($db->affected_rows > 0 ? $db->insert_id : false);
    }

    public static function get($userId, $accountId = null) {
      global $db;

      $sql = "SELECT * FROM accounts WHERE user_id = '" . $db->escape_string($userId) . "'";
      if ($accountId) {
        $sql .= " AND account_id = '" . $db->escape_string($accountId) . "'";
      }
      $sql .= " ORDER BY name ASC";
      return $db->query($sql);
    }

    public static function getByExternalId($userId, $externalId) {
      global $db;

      $sql = "SELECT * FROM accounts WHERE user_id = '" . $db->escape_string($userId) . "' AND external_id = '" . $db->escape_string($externalId) . "'";
      return $db->query($sql);
    }

    public static function find($userId, $name, $status = null) {
      global $db;

      $sql = "SELECT * FROM accounts WHERE user_id = '" . $db->escape_string($userId) . "' AND name = '" . $db->escape_string($name) . "'";
      if ($status) {
        $sql .= " AND status = '" . $db->escape_string($status) . "'";
      }
      return $db->query($sql);
    }

    public static function updateExternalId($userId, $accountId, $externalId) {
      global $db;

      // Update account external id
      $sql = "UPDATE accounts SET external_id = '" . $db->escape_string($externalId) . "', updated_at = NOW() WHERE user_id = '" . $db->escape_string($userId) . "' AND account_id = '" . $db->escape_string($accountId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function updateName($userId, $accountId, $name) {
      global $db;

      // Update account name
      $sql = "UPDATE accounts SET name = '" . $db->escape_string($name) . "', updated_at = NOW() WHERE user_id = '" . $db->escape_string($userId) . "' AND account_id = '" . $db->escape_string($accountId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function updateBalance($userId, $accountId, $balance) {
      global $db;

      // Update account balance
      $sql = "UPDATE accounts SET balance = '" . $db->escape_string($balance) . "', updated_at = NOW() WHERE user_id = '" . $db->escape_string($userId) . "' AND account_id = '" . $db->escape_string($accountId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function updateStatus($userId, $accountId, $status) {
      global $db;

      // Update account status
      $sql = "UPDATE accounts SET status = '" . $db->escape_string($status) . "', updated_at = NOW() WHERE user_id = '" . $db->escape_string($userId) . "' AND account_id = '" . $db->escape_string($accountId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function delete($userId, $accountId) {
      global $db;

      // Delete account
      $sql = "DELETE FROM accounts WHERE user_id = '" . $db->escape_string($userId) . "' AND account_id = '" . $db->escape_string($accountId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }
}