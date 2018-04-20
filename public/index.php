<?php
error_reporting(E_ALL ^ E_NOTICE);
ob_start();
session_start();

require_once("../vendor/autoload.php");
require_once("../app/lib/autoloader.php");
$GLOBALS['autoloader'] = new autoloader();

$GLOBALS['autoloader']->add_dir("../usr/lib/");
$GLOBALS['autoloader']->add_dir("../usr/pages/");
$GLOBALS['autoloader']->add_dir("../app/lib/");
$GLOBALS['autoloader']->add_dir("../app/pages/");

$GLOBALS['database'] = new database(
                            config::get('dbhost'),
                            config::get('dbuser'),
                            config::get('dbpass'),
                            config::get('dbname')
                        );

user::startup();

$router = new router();
$router->add_rewrite("/", "/login");

$router->route();
