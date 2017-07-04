<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "vendor/autoload.php";

CoreApp\Session::init();

date_default_timezone_set(CoreApp\AppConfig::getData("timezone"));
define("APPCONFIG", "server");

$app = new CoreApp\App();
