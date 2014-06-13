<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager        = $event->getApplication()
                                     ->getEventManager();
        
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $shm = $eventManager->getSharedManager();
        $shm->attach('Zend\Mvc\Controller\AbstractActionController', 
                     'dispatch', 
                     array($this, 'authenticationCheck'), 100);
    }
    
    public function authenticationCheck(MvcEvent $event)
    {
        $controller         = $event->getTarget();
        $controllerClass    = get_class($controller);
        $controllerModuleNamespace = substr($controllerClass, 
                                            0, 
                                            strpos($controllerClass, '\\'));
        
        if($controllerModuleNamespace !== __NAMESPACE__) {
            return;
        }
        
        $userService = $controller->getServiceLocator()
                                  ->get('smx.googleauth.service.auth');
        $authService = $userService->getAuthenticationService();
        
        if(! $authService->hasIdentity()) {
            $loginRoute = $userService->getOption('login_route');
            
            return $controller->redirect()
                              ->toRoute($loginRoute);
        } else {
            // tambahin logout di navigation jika user sudah login
            /* @var $navigation \Zend\Navigation\Navigation */
            $navigation = $event->getParam('application')
                                ->getServiceManager()
                                ->get('navigation');
            
            $logoutRoute = $userService->getOption('logout_route');
            $userName    = $authService->getIdentity()->name;
            
            $navigation->addPages(array(
                array(
                    'label' => sprintf("Logout (%s)", $userName),
                    'uri'   => $controller->url()
                                          ->fromRoute($logoutRoute),
                    'icon'  => 'fa fa-fw fa-share-square-o',
                )
            ));
        }
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

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
}
