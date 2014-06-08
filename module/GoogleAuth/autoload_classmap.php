<?php
return array(
    'GoogleAuth\Module'                            => __DIR__ . '/Module.php',
    'GoogleAuth\Authentication\Adapter\GoogleAuth' => __DIR__ . '/src/GoogleAuth/Authentication/Adapter/GoogleAuth.php',
    'GoogleAuth\Controller\IndexController'        => __DIR__ . '/src/GoogleAuth/Controller/IndexController.php',
    'GoogleAuth\Controller\IndexControllerFactory' => __DIR__ . '/src/GoogleAuth/Controller/IndexControllerFactory.php',
    'GoogleAuth\Service\Auth'                      => __DIR__ . '/src/GoogleAuth/Service/Auth.php',
    'GoogleAuth\Service\AuthFactory'               => __DIR__ . '/src/GoogleAuth/Service/AuthFactory.php',
    'GoogleAuth\Service\Session'                   => __DIR__ . '/src/GoogleAuth/Service/Session.php',
    'GoogleAuth\Service\SessionFactory'            => __DIR__ . '/src/GoogleAuth/Service/SessionFactory.php',
);