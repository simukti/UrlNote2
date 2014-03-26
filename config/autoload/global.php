<?php
return array(
    'db' => array(
        'driver'    => 'Pdo_Mysql',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
        'aliases' => array(
            'db.adapter' => 'Zend\Db\Adapter\Adapter'
        )
    ),
);
