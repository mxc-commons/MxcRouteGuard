<?php

namespace MxcRouteGuard\Service;

use Zend\Stdlib\AbstractOptions;

class RouteGuardOptions extends AbstractOptions
{
    /**
     * Turn off strict options mode
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $authService = 'zfcuser_auth_service';
    
    /**
     * @var ListenerAggregateInterface
     */
    protected $strategy = 'MxcRouteGuard\Service\Strategy\RedirectStrategy';
    
    /**
     * @var string
     */
     protected $guardMode = 'white';
    
    /**
     * @var array
     */
     protected $routesObserved = array();
    
    /**
	* @var string
	*/
	protected $anonymousRedirect = 'zfcuser/login';
	
	/**
	 * @return the $authService
	 */
	public function getAuthService() {
		return $this->authService;
	}

	/**
	 * @param string $strategy
	 * @return strategy
	 */
	public function setStrategy($strategy)
	{
		$this->strategy = $strategy;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getStrategy()
	{
		return $this->strategy;
	}
	
	/**
	 * @return the $guardMode
	 */
	public function getGuardMode() {
		return $this->guardMode;
	}

	/**
	 * @return the $routesObserved
	 */
	public function getRoutesObserved() {
		return $this->routesObserved;
	}

	/**
	 * @param string $authService
	 */
	public function setAuthService($authService) {
		$this->authService = $authService;
	}

	/**
	 * @param string $guardMode
	 */
	public function setGuardMode($protectionMode) {
		$this->guardMode = $protectionMode;
	}

	/**
	 * @param multitype: $routesObserved
	 */
	public function setRoutesObserved($routesObserved) {
		$this->routesObserved = $routesObserved;
	}

	/**
	 * @return anonymous redirect route
	 */
	public function getAnonymousRedirect() {
		return $this->anonymousRedirect;
	}
	
	/**
	 * @param string
	 */
	public function setAnonymousRedirect($anonymousRedirect) {
		$this->anonymousRedirect = $anonymousRedirect;
	}
}
    