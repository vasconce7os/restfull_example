<?php
namespace Api;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // tentando resolver o problema do tipo enum
        $em = $e->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
        $platform = $em->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        // fim da treta do enum

        //$e->getApplication()->getServiceManager()->get('translator'); // está sendo feito no bloco acima que trata a compatibilidade do tipo "enum"
        /*$eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        */

        //inicio da tradução
        //$translator = new \Zend\Mvc\I18n\Translator(); // original, esta linha foi iniciada pela seguinte como sugere a documentação oficial em: (http://framework.zend.com/manual/2.2/en/modules/zend.validator.html#translating-messages) e melhor explicada e com outra solução disponível em: (http://stackoverflow.com/questions/23151917/zf2-3-translate-validation-message)
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');
        $translator->addTranslationFile(
            'phpArray',
            './vendor/zendframework/zend-i18n-resources/languages/pt_BR/Zend_Validate.php', //or Zend_Captcha
            'default',
            'pt_BR'
        );
        $translator->setLocale('pt_BR'); //<-- aparentemente despensa uma configuração em ./config/module.config.php ou em config global, peguei este bizu num comentário
        \Zend\Validator\AbstractValidator::setDefaultTranslator($translator);
        //fim tradução
        /*
        $request = $e->getApplication()->getServiceManager()->get('Request');

        print_r($request-> getHeaders());
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_DISPATCH,
                    function($e){
                       print_r($e->getRouteMatch());

                    }
                 );

        exit;
        */

    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
