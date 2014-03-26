<?php
define('PRODUCTION'      , true);
define('DEV_SERVER_NAME' , '127.0.0.1');
define('APPLICATION_PATH', REALPATH(__DIR__));
define('PUBLIC_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'web');
define('CONFIG_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config');
define('MODULE_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'module');
define('DATA_PATH'       , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data');
define('CACHE_PATH'      , DATA_PATH . DIRECTORY_SEPARATOR . 'cache');

$config         = include_once CONFIG_PATH . DIRECTORY_SEPARATOR . 'autoload/env.local.php';
$global_library = $config['environment']['global_library'];
$zf22           = $global_library . DIRECTORY_SEPARATOR . 'zf22';

require_once $zf22 . DIRECTORY_SEPARATOR . 'Zend' . DIRECTORY_SEPARATOR . 'Loader' 
                   . DIRECTORY_SEPARATOR . 'ClassMapAutoloader.php';

use Zend\Loader\ClassMapAutoloader;
use Zend\Mvc\Application;

$loader = new ClassMapAutoloader(array(
    $zf22 . DIRECTORY_SEPARATOR . 'autoload_classmap.php',
));
$loader->register();