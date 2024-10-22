<?php
namespace App\Classes;

class Transaction
{
    var $app;

    function __construct() {
        $this->app = \Slim\Slim::getInstance();
    }

    public static function create($accountId, $externalId, $description, $value, $transactionDate) {
      global $db;

      // Insert new transaction
      $sql = "INSERT INTO transactions (account_id, external_id, description, value, created_at) VALUES ('" . $db->escape_string($accountId) . "', '" . $db->escape_string($externalId) . "', '" . $db->escape_string($description) . "', '" . $db->escape_string($value) . "', '" . $db->escape_string($transactionDate) . "')";
      $db->query($sql);
      return ($db->affected_rows > 0 ? $db->insert_id : false);
    }

    public static function get($accountId, $transactionId = null) {
      global $db;

      $sql = "SELECT * FROM transactions WHERE account_id = '" . $db->escape_string($accountId) . "'";
      if ($transactionId) {
        $sql .= " AND transaction_id = '" . $db->escape_string($transactionId) . "'";
      }
      $sql .= " ORDER BY created_at DESC";
      return $db->query($sql);
    }

    public static function getByExternalId($accountId, $externalId) {
      global $db;

      $sql = "SELECT * FROM transactions WHERE account_id = '" . $db->escape_string($accountId) . "' AND external_id = '" . $db->escape_string($externalId) . "'";
      return $db->query($sql);
    }

    public static function search($accountId, $description) {
      global $db;

      $sql = "SELECT * FROM transactions WHERE account_id = '" . $db->escape_string($accountId) . "' AND description LIKE '%" . $db->escape_string($description) . "'%";
      return $db->query($sql);
    }

    public static function updateExternalId($accountId, $transactionId, $externalId) {
      global $db;

      // Update transaction external id
      $sql = "UPDATE transactions SET external_id = '" . $db->escape_string($externalId) . "', updated_at = NOW() WHERE account_id = '" . $db->escape_string($accountId) . "' AND transaction_id = '" . $db->escape_string($transactionId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function updateDescription($accountId, $transactionId, $description) {
      global $db;

      // Update transaction description
      $sql = "UPDATE transactions SET description = '" . $db->escape_string($description) . "', updated_at = NOW() WHERE account_id = '" . $db->escape_string($accountId) . "' AND transaction_id = '" . $db->escape_string($transactionId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function updateValue($accountId, $transactionId, $value) {
      global $db;

      // Update transaction value
      $sql = "UPDATE transactions SET value = '" . $db->escape_string($value) . "', updated_at = NOW() WHERE account_id = '" . $db->escape_string($accountId) . "' AND transaction_id = '" . $db->escape_string($transactionId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }

    public static function delete($accountId, $transactionId) {
      global $db;

      // Delete transaction
      $sql = "DELETE FROM transactions WHERE account_id = '" . $db->escape_string($accountId) . "' AND transaction_id = '" . $db->escape_string($transactionId) . "'";
      $db->query($sql);
      return ($db->affected_rows > 0);
    }
}