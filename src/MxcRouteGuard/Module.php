<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/MxcRouteGuard for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MxcRouteGuard;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\ListenerAggregateInterface;
use RuntimeException;

class Module implements AutoloaderProviderInterface
{
	protected $whitelist = array ('zfcuser/login');
	
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $em = $app->getEventManager ();
        $sm = $app->getServiceManager ();
        
        $routeGuardService = $sm->get('MxcRouteGuard\Service\RouteGuard');
		$options = $routeGuardService->getOptions();
        $em->attach(MvcEvent::EVENT_ROUTE, array('MxcRouteGuard\Service\Listener\RouteGuardListener', 'onRoute'), -1000);
        if (!$sm->has($options->getStrategy())) {
        	throw new RuntimeException(sprintf('An strategy class with name "%s" does not exist.', $options->getStrategy()));
        }
        $strategy = $sm->get($options->getStrategy());
       	if (!($strategy instanceof ListenerAggregateInterface)) {
       		throw new RuntimeException(sprintf('Strategy class "%s" has to implement ListenerAggregateInterface.', $options->getStrategy()));
       	}
       	$em->attach($strategy);
	}

	/**
	 * @return array|mixed|\Traversable
	 */
	public function getConfig()
	{
		return include __DIR__ . '/../../config/module.config.php';
	}
	
	
	public function getServiceConfig() {
		return array (
			'factories' => array (
				'MxcRouteGuard\Service\Strategy\RedirectStrategy' => 'MxcRouteGuard\Service\RedirectStrategyFactory',
				'MxcRouteGuard\Service\RouteGuard' => 'MxcRouteGuard\Service\RouteGuardFactory'
    		)
    	);
    }
    
    
}
