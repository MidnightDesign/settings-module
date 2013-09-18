<?php

namespace Midnight\Settings\Service;

use Doctrine\ORM\EntityManager;
use Midnight\Settings\Entity\Setting;
use Midnight\Settings\Repository\SettingRepository;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class SettingService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param string $namespace
     * @param string $key
     */
    public function get($namespace, $key)
    {
        return $this->getValue($namespace, $key);
    }

    /**
     * @param string $namespace
     * @param string $key
     */
    public function getValue($namespace, $key)
    {
        $setting = $this->getSetting($namespace, $key);
        if (!$setting) {
            return null;
        }
        return $setting->getValue();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @return SettingRepository
     */
    private function getRepository()
    {
        return $this->getEntityManager()->getRepository('Midnight\Settings\Entity\Setting');
    }

    /**
     * @param string $namespace
     * @param string $key
     * @return Setting
     */
    private function getSetting($namespace, $key)
    {
        return $setting = $this->getRepository()->get($namespace, $key);
    }
}