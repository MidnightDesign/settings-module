<?php

namespace Midnight\Settings\Form;

use Settings\Entity\Setting;
use Settings\Repository\SettingRepository;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class SettingsForm extends Form implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const NAME_SEPARATOR = '::';

    public function __construct(ServiceLocatorInterface $sl)
    {
        parent::__construct();

        $this->setServiceLocator($sl);
        $this->prepareElements();
        $this->loadValues();
    }

    private function prepareElements()
    {
        $config = $this->getConfig();
        foreach ($config as $namespace => $keys) {
            foreach ($keys as $key => $options) {
                if (!isset($options['hidden']) || !$options['hidden']) {
                    $this->add($this->createElement($options, $namespace, $key));
                }
            }
        }
        $this->add(
            [
                'type' => 'Submit',
                'name' => 'submit',
                'attributes' => [
                    'value' => 'Speichern',
                ],
            ]
        );
    }

    private function getConfig()
    {
        return $this->getServiceLocator()->get('Config')['settings'];
    }

    private function createElement($options, $namespace, $key)
    {
        $spec = $options['form_element'];
        $spec['name'] = $this->getElementName($namespace, $key);

        if (isset($spec['options']['object_manager'])) {
            $spec['options']['object_manager'] = $this->getServiceLocator()->get($spec['options']['object_manager']);
        }

        return $this->getFormFactory()->createElement($spec);
    }

    private function loadValues()
    {
        /** @var $repository SettingRepository */
        $repository = $this->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default')
            ->getRepository('Midnight\Settings\Entity\Setting');
        /** @var $setting_values Setting[] */
        $setting_values = $repository->findAll();
        foreach ($setting_values as $value) {
            $element_name = $this->getElementName($value->getNamespace(), $value->getKey());
            if ($this->has($element_name)) {
                $element = $this->get($element_name);
                $element->setValue($value->getValue());
            }
        }
    }

    private function getElementName($namespace, $key)
    {
        return $namespace . self::NAME_SEPARATOR . $key;
    }
}