<?php

namespace MxcRouteGuard\Service;

use RuntimeException;
use MxcRouteGuard\Service\RouteGuard;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RouteGuardFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $config = $sl->get('Configuration');
        $config = isset($config['mxcrouteguard']) ? $config['mxcrouteguard'] : array();

        $routeGuard    = new RouteGuard($config);
        $options = $routeGuard->getOptions();
      
        $authServiceReg = $options->getAuthService();
        if (!$sl->has($authServiceReg)) {
        	throw new RuntimeException(sprintf('An Authentication Service with name "%s" does not exist', $authServiceReg));
        }
        
        $routeGuard->setAuthService($sl->get($authServiceReg));

		// zfcuser registration support
        if ($sl->has('zfcuser_module_options')) {
        	$registrationEnabled = $sl->get('zfcuser_module_options')->getEnableRegistration();
        	if ($registrationEnabled) {
        		$routeGuard->addWhiteListRoute('zfcuser/register');
        	}
        }
        
        return $routeGuard;
    }
}