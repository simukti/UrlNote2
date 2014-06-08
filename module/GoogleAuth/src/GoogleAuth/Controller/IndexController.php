<?php
/**
 * IndexController
 * 
 * @author  Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use GoogleAuth\Service;
use OAuth\Common\Http\Exception\TokenResponseException;

class IndexController extends AbstractActionController
{
    const GOOGLE_USERINFO_URL = 'https://www.googleapis.com/oauth2/v1/userinfo';
    
    /**
     * @var Service\Auth
     */
    protected $authService;
    
    /**
     * @var Service\Session
     */
    protected $sessionService;
    
    /**
     * @param   \GoogleAuth\Service\Auth $authService
     * @return  \GoogleAuth\Controller\IndexController
     */
    public function setAuthService(Service\Auth $authService)
    {
        $this->authService = $authService;
        return $this;
    }
    
    /**
     * @param   \GoogleAuth\Service\Session $sessionService
     * @return  \GoogleAuth\Controller\IndexController
     */
    public function setSessionService(Service\Session $sessionService)
    {
        $this->sessionService = $sessionService;
        return $this;
    }
    
    /**
     * @return  \Zend\View\Model\ViewModel
     */
    public function loginAction()
    {
        $authService = $this->authService
                            ->getAuthenticationService();
        
        if($authService->hasIdentity()) {
            $routeAfterLogin = $this->authService
                                    ->getOption('route_after_login');
            return $this->redirect()
                        ->toRoute($routeAfterLogin);
        }
        
        $view = new ViewModel();
        $view->setVariables(array(
            'domain' => $this->authService
                             ->getOption('domain')
        ));
        
        return $view;
    }
    
    /**
     * @return  \Zend\Http\Response
     */
    public function googleAuthAction()
    {
        $authService = $this->authService
                            ->getAuthenticationService();
        
        if($authService->hasIdentity()) {
            $routeAfterLogin = $this->authService
                                    ->getOption('route_after_login');
            return $this->redirect()
                        ->toRoute($routeAfterLogin);
        }
        
        $this->clearIdentity();
        
        $googleService    = $this->authService
                                 ->getGoogleOauthService();
        $authorizationUri = $googleService->getAuthorizationUri()
                                          ->__toString();
        
        return $this->redirect()
                    ->toUrl($authorizationUri);
    }
    
    /**
     * @return  \Zend\Http\Response
     */
    public function oauthCallbackAction()
    {
        $loginRoute = $this->authService
                           ->getOption('login_route');
        
        if(! count($this->params()->fromQuery())) {
            return $this->redirect()
                        ->toRoute($loginRoute);
        }
        
        $errorParam = $this->params()
                           ->fromQuery('error', false);
        
        if($errorParam !== false) {
            $this->flashMessenger()
                 ->addErrorMessage(sprintf('Error: %s', $errorParam));
            
            return $this->redirect()
                        ->toRoute($loginRoute);
        }
        
        $googleService = $this->authService
                              ->getGoogleOauthService();
        $responseCode  = $this->params()
                              ->fromQuery('code', false);
        
        if(false !== $responseCode) {
            try {
                $googleService->requestAccessToken($responseCode);
                $request  = $googleService->request(self::GOOGLE_USERINFO_URL);
                $identity = Json::decode($request);
                
                $authentication = $this->authService
                                       ->login($identity);
                
                if(! $authentication->isValid()) {
                    $this->flashMessenger()
                         ->addErrorMessage(sprintf("'%s' is out of bounds.", 
                                                    $identity->email));
                    
                    return $this->redirect()
                                ->toRoute($loginRoute);
                }
                
                $routeAfterLogin = $this->authService
                                        ->getOption('route_after_login');
                
                return $this->redirect()
                            ->toRoute($routeAfterLogin);
            } catch (TokenResponseException $exc) {
                $this->flashMessenger()
                     ->addErrorMessage(sprintf(
                                        'Access token tidak bisa '
                                        . 'dikirim lebih dari sekali.'
                                        . ' %s', $exc->getMessage()));
                
                return $this->redirect()
                            ->toRoute($loginRoute);
            }
        }
    }
    
    /**
     * @return  \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->clearIdentity();
        $routeAfterLogout = $this->authService
                                 ->getOption('route_after_logout');
        
        return $this->redirect()
                    ->toRoute($routeAfterLogout);
    }
    
    protected function clearIdentity()
    {
        $authService = $this->authService
                            ->getAuthenticationService();
        
        if($authService->hasIdentity()) {
            $authService->clearIdentity();
        }
        
        $sessionManager = $this->sessionService
                               ->getSessionManager();
        
        if($sessionManager->isValid()) {
            $sessionManager->forgetMe();
            $sessionManager->destroy();
        }
    }
}
