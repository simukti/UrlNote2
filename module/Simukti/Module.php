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
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
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
