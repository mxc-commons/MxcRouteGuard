<?php

namespace MxcRouteGuard\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MxcRouteGuard\Service\Strategy\RedirectStrategy;

class RedirectStrategyFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $sl
     * @return RedirectStrategy
     */
    public function createService(ServiceLocatorInterface $sl)
    {
    	$routeGard = $sl->get('MxcRouteGuard\Service\RouteGuard');
    	$options = $routeGard->getOptions();
        return new RedirectStrategy($options);
    }
}