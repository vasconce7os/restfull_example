<?php
return array(
    'controllers' => array(
        'invokables' => array(
            //'PinssRest\Controller\Users' => 'PinssRest\Controller\UsersController',
            'Api\Controller\Produto' => 'Api\Controller\ProdutoController', // 2016-06-08
            'Api\Controller\Fornecedor' => 'Api\Controller\FornecedorController', // 2016-06-10
            'Api\Controller\Compra' => 'Api\Controller\CompraController', // 2016-06-10
            'Api\Controller\Usuario' => 'Api\Controller\UsuarioController', // 2016-06-14
        ),
    ),

    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'router' => array(
        'routes' => array(


            'api' => array(
                'type'    => 'Literal',
                'options' => array
                (
                    'route'    => '/api',
                    'constraints' => array(
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Produto',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array
                (

                    'produto'=> array
                    (

                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/v1/produto[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Api\Controller\Produto',
                            ),
                        ),
                    ),


                    'fornecedor'=> array
                    (

                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/v1/fornecedor[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Api\Controller\Fornecedor',
                            ),
                        ),
                    ),


                    'compra'=> array
                    (

                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/v1/compra[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Api\Controller\Compra',
                            ),
                        ),
                    ),



                    'usuario'=> array
                    (

                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/v1/usuario[/:id]',
                            'constraints' => array(
                                'id'     => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'Api\Controller\Usuario',
                            ),
                        ),
                    ),


                )
            ),


            /*
            'api' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api/v1/produto[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Produto',
                    ),
                ),
            ),
            */


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
    ),
);
