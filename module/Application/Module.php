<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

// tradução
//use Zend\I18n\Translator\Translator;
//use Zend\Validator\AbstractValidator;
//

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // tradução
        /*
        $translator = new Translator();
        $translator->addTranslationFile(
        'phpArray',
        'vendor/zendframework/zendframework/resources/languages/Zend_Validate.php',
        'default',
        'de_DE'
        );
        AbstractValidator::setDefaultTranslator($translator);
        */
        // fim do bloco tradução


        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $t = $e->getTarget();

//        $t->getEventManager()->attach(
//            $t->getServiceManager()->get('ZfcRbac\View\Strategy\RedirectStrategy')
//        );
        $t->getEventManager()->attach(
            $t->getServiceManager()->get('ZfcRbac\View\Strategy\UnauthorizedStrategy')
        );


        /**
        * create: 2016-01-08
        * Configura um layout para cada módulo 
        * seguindo este tuto: https://www.zf2.com.br/tutoriais/post/fixar-um-layout-diferente-para-cada-modulo e https://github.com/EvanDotPro/EdpModuleLayouts
        */
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractController', 'dispatch', function($e) {
            $controller      = $e->getTarget();
            
            $controllerClass = get_class($controller);
            $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
            $config          = $e->getApplication()->getServiceManager()->get('config');            
            if(isset($config['module_layouts'][$moduleNamespace])) {
                $controller->layout($config['module_layouts'][$moduleNamespace].".phtml");
            }
        }, 100);

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
}
