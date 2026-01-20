<?php

define('BASE_URL', 'http://localhost/development');
define('BASE_PATH', dirname(dirname(__FILE__)));
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'maindb');
define('ASSETS_URL', BASE_URL . '/app/assets');
define('CSS_URL', ASSETS_URL . '/css');
define('JS_URL', ASSETS_URL . '/js');
define('IMAGES_URL', ASSETS_URL . '/img');
define('UPLOADS_URL', BASE_URL . '/app/assets/uploads');
define('CONTROLLERS_URL', BASE_URL . '/app/controllers');
define('VIEWS_URL', BASE_URL . '/app/views');
define('SESSION_TIMEOUT', 60);
error_reporting(E_ALL);
ini_set('display_errors', 1);