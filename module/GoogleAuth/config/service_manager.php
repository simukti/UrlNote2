<?php 
return array(
    'factories' => array(
        'googleauth.service.session'  => 'GoogleAuth\Service\SessionFactory',
        'googleauth.service.auth'     => 'GoogleAuth\Service\AuthFactory',
    ),
    'aliases' => array(
        'Zend\Authentication\AuthenticationService' => 'default_authentication_service',
    ),
    'invokables' => array(
        'default_authentication_service' => 'Zend\Authentication\AuthenticationService',
    ),
);