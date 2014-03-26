<?php
/**
 * Simukti\View\Helper\CurrentRouteFactory
 *
 * Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CurrentRouteFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        $mvcEvent   = $services->getServiceLocator()->get('Application')->getMvcEvent();
        $routeMatch = $mvcEvent->getRouteMatch();
        
        $helper = new CurrentRoute();
        // routeMatch diset dari sini karena jika hanya getMatchedRouteName() yang di-set
        // maka akan error apabila halaman yang diakses notFound.
        $helper->setRouteMatch($routeMatch)
               ->setIsError($mvcEvent->isError());

        return $helper;
    }
}
