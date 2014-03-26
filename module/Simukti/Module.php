<?php
/**
 * Simukti\Module
 *
 * Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassmapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'currentRoute' => 'Simukti\View\Helper\CurrentRouteFactory'
            )
        );
    }
}
