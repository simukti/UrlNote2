<?php
/**
 * Module
 * 
 * @author  Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth;

use Zend\Mvc\MvcEvent;
use Zend\View\Helper\Identity;

class Module 
{
    public function onBootstrap(MvcEvent $event)
    {
        $sessionService = $event->getApplication()
                                ->getServiceManager()
                                ->get('googleauth.service.session');
        
        $sessionService->startSession(); 
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'identity' => function ($services) {
                    $sm     = $services->getServiceLocator();
                    /* @var $authService \Zend\Authentication\AuthenticationService */
                    $authService = $sm->get('googleauth.service.auth')
                                      ->getAuthenticationService();
                    
                    $identityViewHelper = new Identity();
                    $identityViewHelper->setAuthenticationService($authService);
                    return $identityViewHelper;
                }
            )
        );
    }
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassmapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
        );
    }
}
