<?php

error_reporting(E_ALL);

require_once 'config.php';

session_set_cookie_params(time() + COOKIE_TIME);
session_name(APP_NAME);
session_start();

if (isset($_COOKIE[APP_NAME])) {
  setcookie(APP_NAME, $_COOKIE[APP_NAME], time() + COOKIE_TIME, '/');
}

require_once 'app/init.php';

new \app\core\App();

?>