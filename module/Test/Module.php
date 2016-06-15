<?php
namespace Test;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;


// especificação do layout
use Zend\ModuleManager\ModuleManager;
class Module
//tempo
implements
AutoloaderProviderInterface,
ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        //$e->getApplication()->getServiceManager()->get('translator'); // acho que isto era coisa do skeleton zf2, mas aqui uso outro tipo de tradução
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


        $t = $e->getTarget();
        $t->getEventManager()->attach(
            $t->getServiceManager()->get('ZfcRbac\View\Strategy\UnauthorizedStrategy')
        );
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

    public function getServiceConfig()
    {
        
    }



}