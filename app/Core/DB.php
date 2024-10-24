<?php
function db_migrate()
{
  $app = new \Phinx\Console\PhinxApplication();
  $wrap = new \Phinx\Wrapper\TextWrapper($app);

  $wrap->setOption('configuration', $_SERVER['DOCUMENT_ROOT'] . '/../db/phinx.php');
  $migrate = $wrap->getMigrate();
  if ($wrap->getExitCode() !== 0) {
    die('Database migrations failed!');
  }
}

function db_connect()
{
  $db = new mysqli(getenv('DB_HOST') ?? 'localhost', getenv('DB_USER'), getenv('DB_PASS'), getenv('DB_NAME'), getenv('DB_PORT') ?? 3306);

  // Check connection
  if ($db->connect_error) {
    die('Database connection failed: ' . $db->connect_error);
  }
  if (!$db->ping()) {
    die('Database connection ping failed!');
  }

  // Set charset
  $db->set_charset('utf8mb4');

  return $db;
}
