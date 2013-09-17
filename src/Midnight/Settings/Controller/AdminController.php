<?php

namespace Midnight\Settings\Controller;

use Midnight\Admin\Controller\AbstractAdminController;
use Midnight\Settings\Form\SettingsForm;
use Zend\Http\Header\Referer;
use Zend\Http\PhpEnvironment\Request;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractAdminController
{
    public function indexAction()
    {
        $form = new SettingsForm($this->getServiceLocator());

        /** @var $request Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            foreach ($post as $combined_key => $value) {
                if (strstr($combined_key, SettingsForm::NAME_SEPARATOR) !== false) {
                    list($namespace, $key) = explode(SettingsForm::NAME_SEPARATOR, $combined_key);
                    /** @var $repository SettingRepository */
                    $repository = $this->getRepository('Midnight\Settings\Entity\Setting');
                    $repository->set($namespace, $key, $value);
                }
            }

            $this->getEntityManager()->flush();

            $this->flashMessenger()->addMessage('Einstellungen gespeichert.');

            /** @var $referer Referer */
            $referer = $request->getHeader('Referer');
            $url = $referer->getUri();
            return $this->redirect()->toUrl($url);
        }

        return $this->getViewModel(
            array(
                'form' => $form,
            )
        );
    }

    private function getViewModel($variables = null)
    {
        $vm = new ViewModel($variables);
        $vm->setTemplate('settings/admin/' . $this->params()->fromRoute('action') . '.phtml');
        return $vm;
    }
}