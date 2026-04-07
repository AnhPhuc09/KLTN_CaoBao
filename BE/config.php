<?php
const _TAI = true;
const _MODULES = 'dashboard';
const _ACTION = 'index';
const _DEBUG = true;

$envConfig = [];
$envFilePath = __DIR__ . '/.env';
if (file_exists($envFilePath)) {
    $envConfig = parse_ini_file($envFilePath);
}

define('_DRIVER', 'mysql');
define('_HOST', $envConfig['DB_HOST'] ?? 'localhost');
define('_DB', $envConfig['DB_NAME'] ?? 'crawl_news');
define('_USER', $envConfig['DB_USER'] ?? 'root');
define('_PASS', $envConfig['DB_PASS'] ?? '');

define('_HOST_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/KLTN_CaoBao/BE');
define('_FRONTEND_URL', 'http://localhost:3002');
define('_HOST_URL_TEMPLATES', _HOST_URL . '/templates');

define('_PATH_URL', __DIR__);
define('_PATH_URL_TEMPLATES', _PATH_URL . '/templates');

/*API KEYS AND GG CLIENT*/
define('_GEMINI_API_KEY', $envConfig['GEMINI_API_KEY'] ?? '');
define('_GOOGLE_CLIENT_ID', $envConfig['GOOGLE_CLIENT_ID'] ?? '');
define('_GOOGLE_CLIENT_SECRET', $envConfig['GOOGLE_CLIENT_SECRET'] ?? '');
/*Json URL GGSHEET*/
define('_JSON_URL_GGSHEET', $envConfig['JSON_URL_GGSHEET'] ?? '');