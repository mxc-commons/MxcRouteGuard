<?php

namespace MxcRouteGuard\Service\Listener;

use Zend\Mvc\MvcEvent;

class RouteGuardListener
{
    /**
     * @param MvcEvent $e
     */
    public static function onRoute(MvcEvent $e)
    {
        $app         		= $e->getTarget();
        $route       		= $e->getRouteMatch()->getMatchedRouteName();
        $routeGuardService = $app->getServiceManager()->get('MxcRouteGuard\Service\RouteGuard');

        if (!$routeGuardService->isGranted($route)) {
            $e->setError($routeGuardService::ERROR_ROUTE_ANONYMOUS_ACCESS_BLOCKED)
              ->setParam('route', $route);

            $app->getEventManager()->trigger('dispatch.error', $e);
        }
    }
}
