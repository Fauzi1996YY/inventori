<?php

define('APP_NAME', 'Inventori');
define('APP_VERSION', rand(0,999)); /* Should be a version number in production and not rand() */
define('COOKIE_TIME', 604800); /* In seconds */
define('BASE_URL', 'http://localhost/inventori');

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'pass');
define('DB_NAME', 'inventori');

/* Don't change anything below this line */
define('BASE_DIR', dirname(__FILE__));
define('REWRITE_QS', 'appsuniquequerystring');

?>