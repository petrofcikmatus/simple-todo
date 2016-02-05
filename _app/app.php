<?php

// definujeme koreňovú zložku a aplikačnú zložku
if (!defined("ROOT_PATH")) define("ROOT_PATH", dirname(__DIR__));
if (!defined("APP_PATH")) define("APP_PATH", dirname(__FILE__));

// načítame nastavenia
if (!file_exists(APP_PATH . "/config/config.php")) exit("Application error: Configuration not found.");
$config = include(APP_PATH . "/config/config.php");

// zobrazenie errorov
if (isset($config["errors"]) && true == $config["errors"]) {
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);
} else {
    ini_set('display_startup_errors', 0);
    ini_set('display_errors', 0);
    error_reporting(0);
}

// nejaké konštanty ktoré využijeme v aplikácii, napr. vo funkcii url()
if (isset($config["base_url"])) {
    define("BASE_URL", $config["base_url"]);
} else {
    define("BASE_URL", "http://localhost");
}

// nastavenie časovej zóny, date() potom nepíše chyby
if (isset($config["timezone"])) {
    date_default_timezone_set($config["timezone"]);
} else {
    date_default_timezone_set("Europe/Bratislava");
}

// pridáme si triedu pre prácu s databázou a predáme jej nastavenia
require_once(APP_PATH . "/classes/DB.php");

// nastavíme dáta pre pripojenie na databázu
if (isset($config["db_host"])) DB::setDbHost($config["db_host"]);
if (isset($config["db_port"])) DB::setDbPort($config["db_port"]);
if (isset($config["db_name"])) DB::setDbName($config["db_name"]);
if (isset($config["db_user"])) DB::setDbUser($config["db_user"]);
if (isset($config["db_pass"])) DB::setDbPass($config["db_pass"]);

// pridáme si ďalšie súbory s funkciami, ktoré budeme používať
require_once(APP_PATH . "/functions/general.php");
require_once(APP_PATH . "/functions/message.php");
require_once(APP_PATH . "/functions/auth.php");
require_once(APP_PATH . "/functions/tasks.php");

// naštartujeme session potrebné pre prihlásenie a správičky
if (!session_id()) @session_start();

// podstránky ktoré sú dostupné
if (!file_exists(APP_PATH . "/routes.php")) exit("Application error: Routes not found.");
$routes = include(APP_PATH . "/routes.php");

// v prvom segmente url máme podstránku
$page = segment(1);

// ak taká podstránka neexistuje v našom poli dostupných podstránok, tak zobrazíme 404 stránku
if (!isset($routes[$page])) show_404();

// inak ju zobrazíme
show_page($routes[$page]);
