<?php

namespace MxcRouteGuard\Service\Strategy;

use MxcRouteGuard\Service\RouteGuard;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Stdlib\RequestInterface As Response;
use Zend\Mvc\MvcEvent;
use MxcRouteGuard\Service\RouteGuardOptions;

class RedirectStrategy implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var RouteGuardOptions
     */
    protected $options = null;
    
    /**
     * @param RouteGuardOptions
     */
    public function __construct(RouteGuardOptions $options)
    {
    	$this->setOptions($options);
    }

	/**
     * Attach the aggregate to the specified event manager
     *
     * @param  EventManagerInterface $em
     * @return void
     */
    public function attach(EventManagerInterface $em)
    {
        $this->listeners[] = $em->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'prepareRedirect'));
    }

    /**
     * Detach aggregate listeners from the specified event manager
     *
     * @param  EventManagerInterface $em
     * @return void
     */
    public function detach(EventManagerInterface $em)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($em->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Prepare 302 redirect 
     *
     * @param  MvcEvent $e
     * @return void
     */
    public function prepareRedirect(MvcEvent $e)
    {
        // return if we don't have an error
        $error = $e->getError();
        if (empty($error)) return;

        // return if error is not our stuff
        if ($error != RouteGuard::ERROR_ROUTE_ANONYMOUS_ACCESS_BLOCKED) return;
        
        // return if result is a response object
        $result = $e->getResult();
        if ($result instanceof Response) return;

       	// Redirect to the user login page, as an example
       	$router   = $e->getRouter();
       	$request = 	$e->getRequest();
       	$url      = $router->assemble(array(), array('name' => $this->getOptions()->getAnonymousRedirect()));
       	$url = $url.'?redirect='.$request->getRequestUri();
       	$response = $e->getResponse();
       	$response->getHeaders()->addHeaderLine('Location', $url);
       	$response->setStatusCode(302);
    }

    /**
     * @return the $options
     */
    public function getOptions() {
    	return $this->options;
    }
    
    /**
     * @param \MxcRouteGuard\Service\RouteGuardOptions $options
     */
    public function setOptions($options) {
    	$this->options = $options;
    }
    
}
