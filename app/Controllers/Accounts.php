<?php

namespace App\Controllers;

use App\Classes\Account;
use App\Classes\Transaction;
use App\Classes\GoCardless;

class Accounts
{
  var $app;

  function __construct()
  {
    $this->app = \Slim\Slim::getInstance();
  }

  function index()
  {
    global $db;

    // Get accounts list
    $accounts = Account::get($_SESSION['user_id']);

    return $this->app->render('accounts.php', array(
      'title' => 'Accounts',
      'accounts' => $accounts
    ));
  }

  function account($accountId)
  {
    global $db;

    // Get account info
    $accountres = Account::get($_SESSION['user_id'], $accountId);

    // Check account exists
    if ($accountres->num_rows == 0) {
      header('Location: /');
      exit();
    }

    // Get acount details
    $account = $accountres->fetch_assoc();

    // Get transactions list
    $transactions = Transaction::get($accountId);

    return $this->app->render('account.php', array(
      'title' => $account['name'] . ' Account',
      'account' => $account,
      'transactions' => $transactions
    ));
  }

  function new()
  {
    if (!empty($_GET['ref'])) {
      // Get requisition
      $requisition = GoCardless::getRequisition($_GET['ref']);
      if (!empty($requisition['accounts']) && !empty($requisition['status']) && $requisition['status'] == 'LN') {
        foreach ($requisition['accounts'] as $requisitionAccount) {
          // Check for an existing account
          $accountResult = Account::getByExternalId($_SESSION['user_id'], $requisitionAccount);
          if (!empty($accountResult) && $accountResult->num_rows > 0) {
            $accountRes = $accountResult->fetch_assoc();
            $accountId = $accountRes['account_id'];
            // Update account external id
            Account::updateExternalId($_SESSION['user_id'], $accountId, $requisitionAccount);
            // Update account status
            Account::updateStatus($_SESSION['user_id'], $accountId, 'linked');
          } else {
            // Create new account
            $accountId = Account::create($_SESSION['user_id'], $requisitionAccount, $requisitionAccount, 'linked');
          }
          $accountDetails = GoCardless::getAccountDetails($requisitionAccount);
          // Update account name
          Account::updateName($_SESSION['user_id'], $accountId, $accountDetails['account']['displayName'] ?? ($accountDetails['account']['name'] ?? $accountDetails['account']['details']));
        }
        // Redirect to accounts list
        header('Location: /');
        exit();
      } else {
        $_SESSION['errmsg'] = 'Failed to link.';
      }
    } elseif (!empty($_GET['institutionid'])) {
      // Create a requisition link
      $requisition = GoCardless::createRequisition($_GET['institutionid']);
      if (!empty($requisition['id']) && !empty($requisition['link'])) {
        header('Location: ' . $requisition['link']);
        exit();
      } else {
        $_SESSION['errmsg'] = 'Failed to link.';
      }
    } else {
      // Get institutions
      $institutions = GoCardless::getInstitutionsByCountry('gb');
    }

    return $this->app->render('link.php', array(
      'title' => 'New Account',
      'institutions' => $institutions ?? null
    ));
  }

  function sync()
  {
    global $db;

    // Transaction dates
    $transaction_date_from = date('Y-m-d', strtotime('-5 years'));
    $transaction_date_to = date('Y-m-d');

    // Get accounts list
    $accounts = Account::get($_SESSION['user_id']);
    while ($account = $accounts->fetch_assoc()) {
      // Get account balances
      $accountBalances = GoCardless::getAccountBalances($account['external_id']);
      if (!empty($accountBalances['balances'])) {
        // Calculate current balance
        $balance = 0;
        $negativeClosing = false;
        foreach ($accountBalances['balances'] as $accountBalance) {
          // Allowed balance types
          $allowedBalanceTypes = array('closingBooked', 'closingAvailable');
          // Allow interim balance if not a credit card
          if (!$negativeClosing) {
            $allowedBalanceTypes[] = 'interimAvailable';
          }
          // Check balance type is allowed
          if (isset($accountBalance['balanceType']) && in_array($accountBalance['balanceType'], $allowedBalanceTypes) && isset($accountBalance['balanceAmount']['amount'])) {
            $balance += (float)$accountBalance['balanceAmount']['amount'];
            if ($accountBalance['balanceType'] == 'closingBooked' && (float)$accountBalance['balanceAmount']['amount'] < 0) {
              $negativeClosing = true;
            }
          }
        }
        // Update account balance
        Account::updateBalance($_SESSION['user_id'], $account['account_id'], $balance);
      }

      // Get account transactions
      $accountTransactions = GoCardless::getAccountTransactions($account['external_id'], $transaction_date_from, $transaction_date_to);
      if (!empty($accountTransactions['transactions']['booked'])) {
        foreach ($accountTransactions['transactions']['booked'] as $transaction) {
          if (!empty($transaction['internalTransactionId'])) {
            // Check for an existing transaction
            $transactionResult = Transaction::getByExternalId($account['account_id'], $transaction['internalTransactionId']);
            if (empty($transactionResult) || $transactionResult->num_rows == 0) {
              // Define transaction details
              $transactionDetails = ($transaction['remittanceInformationUnstructured'] ?? '');
              if (!empty($transaction['creditorName']) && !str_contains($transactionDetails, $transaction['creditorName'])) {
                $transactionDetails = $transaction['creditorName'] . ' ' . $transactionDetails;
              }
              if (str_contains($transactionDetails, 'Transaction Date:')) {
                $transactionDetails = trim(preg_replace('/Transaction Date: ([0-9]+)-([0-9]+)-([0-9]+)/', '', $transactionDetails), ', ');
              }
              // Create new transaction
              Transaction::create($account['account_id'], $transaction['internalTransactionId'], $transactionDetails, $transaction['transactionAmount']['amount'], date('Y-m-d H:i:s', strtotime($transaction['bookingDateTime'])));
            }
          }
        }
      }
    }

    // Redirect to accounts list
    header('Location: /');
    exit();
  }
}
