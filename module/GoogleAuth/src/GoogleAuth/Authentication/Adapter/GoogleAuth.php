<?php
/**
 * GoogleAuth
 * 
 * @author  Sarjono Mukti Aji <me@simukti.net>
 */
namespace GoogleAuth\Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Authentication\Exception\InvalidArgumentException;
use Zend\Json\Json;

class GoogleAuth implements AdapterInterface
{
    /**
     * Hasil parse basic info dari Google
     * 
     * @var StdClass 
     */
    protected $identity;
    
    /**
     * @var array
     */
    protected $authenticationOptions;
    
    /**
     * @return  array
     */
    public function getAuthenticationOptions() 
    {
        return $this->authenticationOptions;
    }

    /**
     * @param   array $authenticationOptions
     * @return  \GoogleAuth\Authentication\Adapter\GoogleAuth
     */
    public function setAuthenticationOptions(array $authenticationOptions)
    {
        $this->authenticationOptions = $authenticationOptions;
        return $this;
    }

    /**
     * @param   mixed $identity
     * @return  \GoogleAuth\Authentication\Adapter\GoogleAuth
     */
    public function setIdentity($identity) 
    {
        if(is_string($identity)) {
            $identity = Json::decode($identity);
        }
        
        $this->identity = $identity;
        return $this;
    }
    
    /**
     * @return  StdClass
     */
    public function getIdentity() 
    {
        return $this->identity;
    }
    
    /**
     * @return  \Zend\Authentication\Result
     * @throws  InvalidArgumentException
     */
    public function authenticate() 
    {
        if(! $this->getIdentity()) {
            throw new InvalidArgumentException(
                        'Identity or credential was not provided.'
            );
        }
        
        $validUser = $this->validateUser();
        
        if(false === $validUser) {
            $code    = Result::FAILURE;
            $message = sprintf("Domain '%s' is not valid.", 
                                $this->getIdentity()->hd);
        } else {
            $code    = Result::SUCCESS;
            $message = 'Authentication Success.';
        }
        
        $result = new Result($code, $validUser, array($message));
        return $result;
    }
    
    /**
     * Validate user email domain
     * 
     * @return  StdClass|false
     */
    protected function validateUser()
    {
        $identity = $this->getIdentity();
        $options  = $this->getAuthenticationOptions();
        $domain   = $options['domain'];
        
        if( $identity->hd !== $domain) {
            return false;
        }
        
        return $identity;
    }
}
