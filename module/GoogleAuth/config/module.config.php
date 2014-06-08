<?php 
return array(
    'auth'            => include_once __DIR__ . DIRECTORY_SEPARATOR . 'auth.php',
    'router'          => include_once __DIR__ . DIRECTORY_SEPARATOR . 'router.php',
    'service_manager' => include_once __DIR__ . DIRECTORY_SEPARATOR . 'service_manager.php',
    'controllers'     => include_once __DIR__ . DIRECTORY_SEPARATOR . 'controllers.php',
    'view_manager'    => include_once __DIR__ . DIRECTORY_SEPARATOR . 'view_manager.php',
    'module_layouts'  => array(
      'GoogleAuth' => 'google-auth/layout'
    ),
);