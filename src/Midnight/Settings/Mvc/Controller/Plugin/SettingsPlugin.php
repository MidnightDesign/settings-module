<?php

namespace Midnight\Settings\Mvc\Controller\Plugin;

use Midnight\Settings\Service\SettingService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class SettingsPlugin extends AbstractPlugin
{
    public function __invoke($namespace, $key)
    {
        $controller = $this->getController();
        if (!$controller instanceof ServiceLocatorAwareInterface) {
            throw new \Exception('The controller must be an instance of Zend\ServiceManager\ServiceLocatorAwareInterface');
        }
        $ss = $controller->getServiceLocator()->get('settings');
        if (!$ss instanceof SettingService) {
            throw new \Exception('Expected to get an instance of Midnight\Settings\Service\SettingService from service manager key "settings"');
        }
        return $ss->getValue($namespace, $key);
    }

}