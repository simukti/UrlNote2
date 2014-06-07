<?php
define('PRODUCTION'      , true);
define('DEV_SERVER_NAME' , '127.0.0.1');
define('APPLICATION_PATH', REALPATH(__DIR__));
define('PUBLIC_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'web');
define('CONFIG_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'config');
define('MODULE_PATH'     , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'module');
define('DATA_PATH'       , APPLICATION_PATH . DIRECTORY_SEPARATOR . 'data');
define('CACHE_PATH'      , DATA_PATH . DIRECTORY_SEPARATOR . 'cache');

// Composer autoloading
if (file_exists('vendor/autoload.php')) {
    $loader = include 'vendor/autoload.php';
}

if (class_exists('Zend\Loader\AutoloaderFactory')) {
    return;
}

$zf2Path = false;

if (is_dir('vendor/ZF2/library')) {
    $zf2Path = 'vendor/ZF2/library';
} elseif (getenv('ZF2_PATH')) {      // Support for ZF2_PATH environment variable or git submodule
    $zf2Path = getenv('ZF2_PATH');
} elseif (get_cfg_var('zf2_path')) { // Support for zf2_path directive value
    $zf2Path = get_cfg_var('zf2_path');
}

if ($zf2Path) {
    if (isset($loader)) {
        $loader->add('Zend', $zf2Path);
        $loader->add('ZendXml', $zf2Path);
    } else {
        include $zf2Path . '/Zend/Loader/AutoloaderFactory.php';
        Zend\Loader\AutoloaderFactory::factory(array(
            'Zend\Loader\StandardAutoloader' => array(
                'autoregister_zf' => true
            )
        ));
    }
}

if (!class_exists('Zend\Loader\AutoloaderFactory')) {
    throw new RuntimeException('Unable to load ZF2. Run `php composer.phar install` or define a ZF2_PATH environment variable.');
}