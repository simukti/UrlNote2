<?php
/**
 * GoogleAuth\Service\Session
 *
 * Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth\Service;

use Zend\Session\SessionManager;
use Zend\Session\Container as SessionContainer;

class Session
{
    const SESSION_NAMESPACE = 'SIMUKTI_AUTHENTICATION';
    
    protected $sessionNamespace = self::SESSION_NAMESPACE;
    protected $sessionManager;
    protected $sessionContainer;
    
    /**
     * @param   \Zend\Session\SessionManager $sessionManager
     * @return  \GoogleAuth\Service\Session
     */
    public function setSessionManager(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
        return $this;
    }
    
    /**
     * @return  \Zend\Session\SessionManager
     */
    public function getSessionManager()
    {
        return $this->sessionManager;
    }
    
    /**
     * @see     \Zend\Session\SessionManager::start()
     * @param   boolean $preserveStorage
     */
    public function startSession($preserveStorage = false)
    {
        $sessionManager = $this->getSessionManager();
        if(! $sessionManager->sessionExists()) {
            $sessionManager->start($preserveStorage);
        }
    }
    
    /**
     * @return  \Zend\Session\AbstractContainer
     */
    public function getSessionContainer()
    {
        if(null === $this->sessionContainer 
                && ! $this->sessionContainer instanceof SessionContainer) 
        {
            $this->sessionContainer = new SessionContainer($this->sessionNamespace);
        }
        return $this->sessionContainer;
    }
}