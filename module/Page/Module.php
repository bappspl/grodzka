<?php

namespace Page;

use CmsIr\Page\Model\Page;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $e->getApplication()->getServiceManager();
        $menu = $this->getMenuService($sm)->getMenuByMachineName('main-menu');

        /* @var $polecaneMiejsca Page */
        $polecaneMiejsca = $this->getPageService($sm)->findOneBySlug('polecane-miejsca');
        $polecane = $polecaneMiejsca->getContent();

        $ciastka = $this->getPageService($sm)->findOneBySlug('ciastka');
        $c = $ciastka->getContent();

        $viewModel = $e->getViewModel();
        $viewModel->menu = $menu;
        $viewModel->polecane = $polecane;
        $viewModel->ciastka = $c;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return \CmsIr\Menu\Service\MenuService
     */
    public function getMenuService($sm)
    {
        return $sm->get('CmsIr\Menu\Service\MenuService');
    }

    /**
     * @return \CmsIr\Page\Service\PageService
     */
    public function getPageService($sm)
    {
        return $sm->get('CmsIr\Page\Service\PageService');
    }
}
