<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/[page/:page]',
                    'constraints' => array(
                        'page' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'index',
                        'page'       => '1',
                    ),
                ),
            ),
            'add' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/add',
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'add',
                    ),
                ),
            ),
            'edit' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/edit/:id',
                    'constraints' => array(
                        'id' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'edit',
                    ),
                ),
            ),
            'delete' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/delete/:id/:token',
                    'constraints' => array(
                        'id'    => '\d+',
                        'token' => '[0-9a-fA-F]+',
                    ),
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'delete',
                    ),
                ),
            ),
            'tags' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/tags',
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'tags',
                    ),
                ),
            ),
            'tag' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/tag/:slug[/page/:page]',
                    'constraints' => array(
                        'slug' => '[a-z0-9\-_]+',
                        'page' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'application.controller.index',
                        'action'     => 'tag',
                        'page'       => '1',
                    ),
                ),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
            'add' => array(
                'label' => 'New Url',
                'route' => 'add',
                'icon'  => 'fa fa-fw fa-bookmark',
                'pages' => array()
            ),
            'tags' => array(
                'label' => 'Tags',
                'route' => 'tags',
                'icon'  => 'fa fa-fw fa-tags',
                'pages' => array()
            ),
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'cache'      => 'Zend\Cache\Service\StorageCacheFactory',
            'url'        => 'Application\Service\UrlFactory',
        ),
    ),
    'cache' => array(
        'adapter' => array(
            'name' => 'filesystem'
        ),
        'options' => array(
            'cache_dir' => CACHE_PATH,
            'dir_level' => 0,
            'file_permission' => 0666,
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'application.controller.index' => 'Application\Controller\IndexControllerFactory'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => (PRODUCTION ? false : true),
        'display_exceptions'       => (PRODUCTION ? false : true),
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'layout/flashmessenger'   => __DIR__ . '/../view/layout/flashmessenger.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
