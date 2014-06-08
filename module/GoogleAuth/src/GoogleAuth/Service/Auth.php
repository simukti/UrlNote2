<?php
/**
 * Auth
 * 
 * @author  Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth\Service;

use Zend\Authentication\AuthenticationService;
use OAuth\OAuth2\Service\Google;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Storage\Session as OauthSession;
use OAuth\Common\Consumer\Credentials;

class Auth
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService;
    
    /**
     * Options dari local.config.php di konfigurasi aplikasi
     * 
     * @var array
     */
    protected $options;
    
    /**
     * @return  array
     */
    public function getOptions() 
    {
        return $this->options;
    }

    /**
     * @param   array $options
     * @return  \GoogleAuth\Service\Auth
     */
    public function setOptions(array $options = array()) 
    {
        $this->options = $options;
        return $this;
    }
    
    /**
     * @param   string $key
     * @param   mixed $value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }
    
    /**
     * @param   string $key
     * @return  mixed
     */
    public function getOption($key)
    {
        if(isset($this->options[$key])) {
            return $this->options[$key];
        }
    }
    
    /**
     * AuthenticationService service ini 
     * sudah harus di set $adapter dan $storage - nya melalui factory.
     * 
     * @return  \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService() 
    {
        return $this->authenticationService;
    }
    
    /**
     * @param   \Zend\Authentication\AuthenticationService $authenticationService
     * @return  \GoogleAuth\Service\Auth
     */
    public function setAuthenticationService(AuthenticationService $authenticationService) 
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }
    
    /**
     * @return  \OAuth\OAuth2\Service\Google
     */
    public function getGoogleOauthService()
    {
        $credentials  = new Credentials(
            $this->getOption('clientId'),
            $this->getOption('clientSecret'),
            // callbackUrl di-set dari \GoogleAuth\Service\AuthFactory
            $this->getOption('callbackUrl')
        );

        $googleOauthService = new Google($credentials, 
                                        new StreamClient(), 
                                        new OauthSession(), [
                                            Google::SCOPE_USERINFO_EMAIL,
                                            Google::SCOPE_USERINFO_PROFILE
                                        ]);
        
        return $googleOauthService;
    }
    
    /**
     * @param   StdClass $identity
     * @return  \Zend\Authentication\Result
     */
    public function login($identity)
    {
        $authAdapter = $this->getAuthenticationService()
                            ->getAdapter();
        $authAdapter->setIdentity($identity);
        
        $validation = $this->authenticationService
                           ->authenticate($authAdapter);
        
        return $validation;
    }
}
