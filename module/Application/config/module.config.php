<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(

            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),


            'fornecedor' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/fornecedor',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Fornecedor',
                        'action' => 'index'
                    ),
                ),
            ),



            'fornecedor' => array(

                'type'    => 'segment',
                'options' => array(
                    'route'    => '/fornecedor[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Fornecedor',
                        'action'     => 'index',
                    ),
                ),
            ),

            'produto' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/produto[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Produto',
                        'action'     => 'index',
                    ),
                ),
            ),


            'usuario' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/usuario[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Usuario',
                        'action'     => 'index',
                    ),
                ),
            ),


            'admin' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Admin',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'other' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/other[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'other',
                    ),
                ),
            ),

            'post' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/post',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Post',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'read' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => 'view|list',
                                'id'       => '[0-9]+'
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'add' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'action'     => 'add',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action[/:id]]',
                            'constraints' => array(
                                'action'     => 'edit|delete',
                                'id'       => '[0-9]+'
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),





//                'child_routes' => array(
//                    // Segment route for viewing one blog post
//                    'view' => array(
//                        'type' => 'Literal',
//                        'options' => array(
//                            'route' => '/view',
//                            'defaults' => array(
//                                'controller' => 'Application\Controller\Post',
//                                'action'     => 'view',
//                            ),
//                        ),
//                    ),
//                    'add' => array(
//                        'type' => 'Literal',
//                        'options' => array(
//                            'route' => '/add',
//                            'defaults' => array(
//                                'controller' => 'Application\Controller\Post',
//                                'action'     => 'add',
//                            ),
//                        ),
//                    ),
//                    'edit' => array(
//                        'type' => 'Literal',
//                        'options' => array(
//                            'route' => '/edit[/:id]',
//                            'constraints' => array(
//                                'id' => '[0-9]+',
//                            ),
//                            'defaults' => array(
//                                'controller' => 'Application\Controller\Post',
//                                'action'     => 'edit',
//                            ),
//                        ),
//                    ),
//                ),
            ),
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
            'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
        'aliases' => array(
            'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager',
            'Zend\Authentication\AuthenticationService' => 'zfcuser_auth_service',
        ),
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Fornecedor' => 'Application\Controller\FornecedorController', // 2016-06-10
            'Application\Controller\Produto' => 'Application\Controller\ProdutoController', // 2016-06-10
            'Application\Controller\Usuario' => 'Application\Controller\UsuarioController', // 2016-06-14
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(

            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            //'layout/layout'           => 'module/Application/view/layout/layout.phtml',

            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            ),
        ),
    ),
);
