<?php

namespace Midnight\Settings\Mvc\Controller\Plugin;

use Midnight\Settings\Service\SettingService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class SettingsPlugin extends AbstractPlugin
{
    /**
     * @param string $namespace
     * @param string $key
     * @param string|null $value
     * @return string|bool
     * @throws \Exception
     */
    public function __invoke($namespace, $key, $value = null)
    {
        $controller = $this->getController();
        if (!$controller instanceof ServiceLocatorAwareInterface) {
            throw new \Exception('The controller must be an instance of Zend\ServiceManager\ServiceLocatorAwareInterface');
        }
        $ss = $controller->getServiceLocator()->get('settings');
        if (!$ss instanceof SettingService) {
            throw new \Exception('Expected to get an instance of Midnight\Settings\Service\SettingService from service manager key "settings"');
        }
        if (is_null($value)) {
            return $ss->get($namespace, $key);
        } elseif (is_string($value)) {
            $ss->set($namespace, $key, $value);
            return true;
        } else {
            throw new \Exception('The third argument must be a string.');
        }
    }

}