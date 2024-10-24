<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Language" content="en">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="copyright" content="Money Dashboard">
  <meta name="description" content="A dashboard for your money.">

  <link rel="canonical" href="<?php echo basename($_SERVER['REQUEST_URI']); ?>">

  <title><?php echo $title ?? ''; ?> | Money Dashboard</title>

  <!-- Fonts -->
  <link rel="preconnect" href="//fonts.gstatic.com">
  <link href="//fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,700;1,400;1,700&family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <!-- Styles -->
  <link rel="stylesheet" href="/css/normalize.css">
  <link rel="stylesheet" href="/css/app.css">
</head>

<body id="page-top">
  <header>
    <a href="/" title="Money Dashboard" class="logo"><?php echo file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/images/icon.svg'); ?><?php echo (\App\Classes\User::isLoggedIn() ? ucwords($_SESSION['username']) . "'s " : ""); ?>Money Dashboard</a>
    <nav>
      <ul>
        <?php if (\App\Classes\User::isLoggedIn()) { ?>
          <li><a href="/accounts/new" title="New">New</a></li>
          <li><a href="/accounts/sync" title="Sync">Sync</a></li>
          <li><a href="/logout" title="Logout">Logout</a></li>
        <?php } elseif ($title == 'Login') { ?>
          <li><a href="/register" title="Register">Register</a></li>
        <?php } else { ?>
          <li><a href="/login" title="Login">Login</a></li>
        <?php } ?>
        <li id="theme-toggle"></li>
      </ul>
    </nav>
  </header>

  <main>
    <?php
    if (!empty($_SESSION['errmsg'])) {
      echo '<div class="errmsg">' . $_SESSION['errmsg'] . '</div>';
    } elseif (!empty($_SESSION['okmsg'])) {
      echo '<div class="okmsg">' . $_SESSION['okmsg'] . '</div>';
    }
    ?>