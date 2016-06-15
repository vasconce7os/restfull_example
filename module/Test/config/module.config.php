<?php
return array(
    'router' => array(
        'routes' => array(
			'testc' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/testc',
                    'defaults' => array(
                        'controller' => 'Test\Controller\Testc',
                        'action' => 'index'
                    ),
                ),
			),

        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Test\Controller\Testc'			=>    'Test\Controller\TestcController',
            'Test\Controller\Index'  =>           'Test\Controller\IndexController',
        ),
    ),

    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    )
);
