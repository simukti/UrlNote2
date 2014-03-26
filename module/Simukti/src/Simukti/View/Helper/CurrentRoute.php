<?php
/**
 * Simukti\View\Helper\CurrentRoute
 *
 * Sarjono Mukti Aji <me@simukti.net>
 */
namespace Simukti\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Router\Http\RouteMatch;

class CurrentRoute extends AbstractHelper
{
    /**
     * Status apakah request error atau tidak
     * 
     * @var boolean
     */
    protected $isError;
    
    /**
     * @var RouteMatch
     */
    protected $routeMatch;
    
    /**
     * @return  boolean
     */
    public function getIsError()
    {
        return $this->isError;
    }

    /**
     * @param   boolean $isError
     * @return  \Simukti\View\Helper\CurrentRoute
     */
    public function setIsError($isError)
    {
        $this->isError = $isError;
        return $this;
    }

    /**
     * @return  \Zend\Mvc\Router\Http\RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * @param   \Zend\Mvc\Router\Http\RouteMatch $routeMatch
     * @return  \Simukti\View\Helper\CurrentRoute
     */
    public function setRouteMatch(RouteMatch $routeMatch = null)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }
    
    /**
     * Periksa apakah nama route yang di berikan adalah route yang sedang diakses. Atau ambil nama route yang sedang 
     * diakses.
     * 
     * @param   string  $route_name     default null
     * @return  boolean|string|array
     */
    public function __invoke($route_name = null) 
    {
        if (null === $route_name) {
            return $this->getRouteMatch()->getMatchedRouteName();
        }
        
        return $this->isRouteNameMatch($route_name);
    }
    
    /**
     * @param   string|array $route_name
     * @return  boolean
     */
    protected function isRouteNameMatch($route_name)
    {
        if($this->getIsError()) {
            return false;
        }
        
        $routeMatch     = $this->getRouteMatch();
        $matchedRoute   = $routeMatch->getMatchedRouteName();
        
        if(is_array($route_name)) {
            return (in_array($matchedRoute, $route_name)) ? true : false;
        } else {
            return (trim($route_name) === $matchedRoute) ? true : false;
        }
    }
}
