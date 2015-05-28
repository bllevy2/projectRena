<?php
// Imports
use Slim\Slim;
use Slim\Views\Twig;
use ProjectRena\Lib\SessionHandler;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

// Error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load the autoloader
if(file_exists(__DIR__."/vendor/autoload.php"))
    require_once(__DIR__."/vendor/autoload.php");
else
    throw new Exception("vendor/autoload.php not found, make sure you run composer install");

// Require the config
if(file_exists(__DIR__."/config.php"))
    require_once(__DIR__."/config.php");
else
    throw new Exception("config.php not found (you might wanna start by copying config_new.php)");

// Prepare app
$app = new Slim($config["slim"]);

// Session
$session = new SessionHandler();
session_set_save_handler($session, true);
session_cache_limiter(false);
session_start();

// Launch Whoops
$app->add(new WhoopsMiddleware);

// Prepare view
$app->view(new Twig());
$app->view->parserOptions = $config["twig"];

// load the additional configs
$configFiles = glob(__DIR__ . "/config/*.php");
foreach($configFiles as $configFile)
    require_once($configFile);

// Run app
$app->run();
