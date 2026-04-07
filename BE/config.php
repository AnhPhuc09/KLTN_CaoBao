<?php
const _TAI = true;
const _MODULES = 'dashboard';
const _ACTION = 'index';
const _DEBUG = true;

define('_DRIVER', 'mysql');       
define('_HOST', 'localhost');     
define('_DB', 'crawl_news');       
define('_USER', 'root');          
define('_PASS', '');  

define('_HOST_URL', 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/KLTN_CaoBao/BE');
define('_FRONTEND_URL', 'http://localhost:3002');
define('_HOST_URL_TEMPLATES', _HOST_URL . '/templates');

define('_PATH_URL',__DIR__);
define('_PATH_URL_TEMPLATES',_PATH_URL.'/templates'); 