<?php

namespace MxcRouteGuard\Service;

class RouteGuard
{
    const ERROR_ROUTE_ANONYMOUS_ACCESS_BLOCKED    = 'error-route-anonymous-access-blocked';

    /**
     * @var RouteGuardOptions
     */

    protected $options;

    /**
     * @var Service\AuthServiceInterface
     */
    protected $authService;

    /**
     * @var Service\AuthServiceInterface
     */
    protected $whitelist = array();
    
	/**
     * @param array $options
     */
    public function __construct(array $options = array())
    {
        $this->options = new RouteGuardOptions($options);
        $this->addWhiteListRoute($this->options->getAnonymousRedirect());
    }
   
    /**
     * @return the $whitelist
     */
    public function getWhitelist() {
    	return $this->whitelist;
    }
    
    /**
     * @param string
     */
    public function addWhiteListRoute($route) {
		$this->whitelist[] = $route;
    }
    	
    /**
     * @return Service\AuthServiceInterface
     */
    public function getAuthService()
    {
    	return $this->authService;
    }
    
    /**
     * @param  Service\AuthServiceInterface
     * @return RouteGuard
     */
    public function setAuthService($authService)
    {
    	$this->authService = $authService;
    	return $this;
    }
    
    /**
     * @return RouteGuardOptions
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    public function isGranted($route)
    {
		// if authenticated return true
    	if ($this->getAuthService()->hasIdentity ()) {
    		return true;
    	}
    	 
		// if route is anonymous redirect route
		// or zfcuser registrion (enable_registration == true)
    	$list = $this->getWhiteList();
    	if (in_array($route,$list)) {
    		return true;
    	}
    
    	$list = $this->getOptions()->getRoutesObserved();

    	switch ($this->getOptions()->getGuardMode()) {
	    	case 'white':
	    		// if whitelisted return true
		    	if (in_array ( $route, $list )) {
		    		return true;
		    	}
		    	break;
	    	case 'black':
	    		// if not blacklisted return true
	    		if (!in_array ( $route, $list )) {
	    			return true;
	    		}
	    		break;
	    	default:
	    		// unknown guard mode
	    		return false;
	    		break;
    	}
    
    	return false;
    }
}