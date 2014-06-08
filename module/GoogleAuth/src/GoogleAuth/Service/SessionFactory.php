<?php
/**
 * SessionFactory
 * 
 * @author  Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

class SessionFactory implements FactoryInterface
{
    /**
     * @param   \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return  \GoogleAuth\Service\Session
     */
    public function createService(ServiceLocatorInterface $serviceLocator) 
    {
        $appConfigs      = $serviceLocator->get('Config');
        $sessionConfig   = new SessionConfig();
        $sessionConfig->setOptions($appConfigs['auth']['session']);
        
        $session = new Session();
        $session->setSessionManager(new SessionManager($sessionConfig));
        
        return $session;
    }
}
