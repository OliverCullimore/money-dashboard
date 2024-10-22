<?php

namespace App\Classes;

class GoCardless
{
  function __construct() {}

  public static function makeRequest($path, $data = null, $headers = array('Accept: application/json'), $method = null)
  {
    $curl = curl_init("https://bankaccountdata.gocardless.com/api/" . $path);
    curl_setopt_array($curl, array(
      CURLOPT_HTTPHEADER => $headers,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT => 10
    ));
    if ($data) {
      curl_setopt_array($curl, array(
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data
      ));
    }
    if ($method) {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    }

    // Make request
    $response = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Check response is valid
    if (!empty($code) && $code >= 200 && $code < 400) {
      $response = json_decode($response, true);
    }

    return array('code' => $code, 'response' => $response);
  }

  public static function getAccessToken()
  {
    // Check for a valid access token
    if ($accessToken = Token::get('gocardless_access_token')) {
      return $accessToken['value'];
    }
    // Check session for a valid refresh token
    if ($refreshToken = Token::get('gocardless_refresh_token')) {
      return self::refreshToken($refreshToken['value']);
    }
    $data = array(
      'secret_id' => getenv('GOCARDLESS_SECRET_ID'),
      'secret_key' => getenv('GOCARDLESS_SECRET_KEY')
    );
    $headers = array(
      'Accept: application/json',
      'Content-Type: application/json'
    );
    $result = self::makeRequest('v2/token/new/', json_encode($data), $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      if (!empty($result['response']['access']) && !empty($result['response']['access_expires'])) {
        Token::store('gocardless_access_token', $result['response']['access'], date('Y-m-d H:i:s', time() + $result['response']['access_expires']));
        if (!empty($result['response']['refresh']) && !empty($result['response']['refresh_expires'])) {
          Token::store('gocardless_refresh_token', $result['response']['refresh'], date('Y-m-d H:i:s', time() + $result['response']['refresh_expires']));
        }
        return $result['response']['access'];
      }
    }
    return false;
  }

  public static function refreshToken($refreshToken)
  {
    $data = array(
      'refresh' => $refreshToken
    );
    $headers = array(
      'Accept: application/json',
      'Content-Type: application/json'
    );
    $result = self::makeRequest('v2/token/refresh/', json_encode($data), $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      if (!empty($result['response']['access']) && !empty($result['response']['access_expires'])) {
        Token::store('gocardless_access_token', $result['response']['access'], date('Y-m-d H:i:s', time() + $result['response']['access_expires']));
        return $result['response']['access'];
      }
    }
    return false;
  }

  public static function getInstitutionsByCountry($countryCode = 'gb')
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/institutions/?country=' . $countryCode, null, $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }

  public static function createRequisition($institutionId)
  {
    $data = array(
      'redirect' => getenv('BASE_URL') . '/accounts/new',
      'institution_id' => $institutionId,
      //'reference' => '',
      //'agreement' => '',
      //'user_language' => 'EN'
    );
    $headers = array(
      'Accept: application/json',
      'Content-Type: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/requisitions/', json_encode($data), $headers);
    if (!empty($result['code']) && $result['code'] == 201) {
      return $result['response'];
    }
    return array();
  }

  public static function getRequisition($ref = null)
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/requisitions/' . ($ref ? $ref . '/' : ''), null, $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }

  public static function deleteRequisition($ref)
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/requisitions/' . $ref . '/', null, $headers, 'DELETE');
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }

  public static function getAccountDetails($id)
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/accounts/' . $id . '/details/', null, $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }

  public static function getAccountBalances($id)
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/accounts/' . $id . '/balances/', null, $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }

  public static function getAccountTransactions($id, $date_from = null, $date_to = null)
  {
    $headers = array(
      'Accept: application/json',
      'Authorization: Bearer ' . self::getAccessToken()
    );
    $result = self::makeRequest('v2/accounts/' . $id . '/transactions/' . ($date_from ? '?date_from=' . $date_from : '') . ($date_to ? (!$date_from ? '?' : '&') . 'date_to=' . $date_to : ''), null, $headers);
    if (!empty($result['code']) && $result['code'] == 200) {
      return $result['response'];
    }
    return array();
  }
}
