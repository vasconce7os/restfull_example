<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

use ZfcRbac\Guard\GuardInterface;

/**
 * Copy-paste this file to your config/autoload folder (don't forget to remove the .dist extension!)
 */

return [
    'zfc_rbac' => [
        /**
         * Key that is used to fetch the identity provider
         *
         * Please note that when an identity is found, it MUST implements the ZfcRbac\Identity\IdentityProviderInterface
         * interface, otherwise it will throw an exception.
         */
        //'identity_provider' => 'ZfcRbac\Identity\AuthenticationIdentityProvider',

        /**
         * Set the guest role
         *
         * This role is used by the authorization service when the authentication service returns no identity
         */
        'guest_role' => 'guest',

        /**
         * Set the guards
         *
         * You must comply with the various options of guards. The format must be of the following format:
         *
         *      'guards' => [
         *          'ZfcRbac\Guard\RouteGuard' => [
         *              // options
         *          ]
         *      ]
         */
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'home'             => ['*'], // !!! nunca alterar home, a treta é grande
                'other'            => ['*'],
                'tst'              =>['*'],
                'testc*'           =>['*'],
                'publico*'         => ['*'],
                'sitemap*'         => ['*'],
                'learn-zf2-pagination'=> ['*'],
                'admin*'           => ['admin'],
                'post'             => ['admin'],
                'post/read'        => ['guest'],
                'post/add'         => ['user'],
                'post/edit'        => ['admin'],
                'zfcuser'          => ['user'],
                'zfcuser/logout'   => ['user'],
                'zfcuser/login'    => ['guest'],
                'zfcuser/register' => ['guest'],

                'zfcuser/logout'   => ['*'],
                'zfcuser/login'    => ['*'],
                'zfcuser/register' => ['*'],

                'api*'    => ['*'], // 2016-06-08

                'album*'    => ['*'], // 2016-06-08
                'produto*'    => ['*'], // 2016-06-08
                'fornecedor*'    => ['*'], // 2016-06-10
                'usuario*'    => ['*'], // 2016-06-14
            ]
        ],

        /**
         * As soon as one rule for either route or controller is specified, a guard will be automatically
         * created and will start to hook into the MVC loop.
         *
         * If the protection policy is set to DENY, then any route/controller will be denied by
         * default UNLESS it is explicitly added as a rule. On the other hand, if it is set to ALLOW, then
         * not specified route/controller will be implicitly approved.
         *
         * DENY is the most secure way, but it is more work for the developer
         */
        'protection_policy' => \ZfcRbac\Guard\GuardInterface::POLICY_DENY,

        /**
         * Configuration for role provider
         *
         * It must be an array that contains configuration for the role provider. The provider config
         * must follow the following format:
         *
         *      'ZfcRbac\Role\InMemoryRoleProvider' => [
         *          'role1' => [
         *              'children'    => ['children1', 'children2'], // OPTIONAL
         *              'permissions' => ['edit', 'read'] // OPTIONAL
         *          ]
         *      ]
         *
         * Supported options depend of the role provider, so please refer to the official documentation
         */
        'role_provider' => [
            'ZfcRbac\Role\InMemoryRoleProvider' => [
                'admin' => [
                    'children' => ['user'],
                    'permissions' => ['post.read', 'post.add', 'post.edit', 'post']
                ],
                'user'  => [
                    'children' => ['guest'],
                    'permissions' => ['post.read', 'post.add']
                ],
                'guest' => [
                    'permissions' => ['post.read']
                ]
            ]
//            'ZfcRbac\Role\ObjectRepositoryRoleProvider' => [
//                'object_manager'     => 'doctrine.entitymanager.orm_default',
//                'class_name'         => 'Application\Entity\FlatRole',
//                'role_name_property' => 'name'
//            ]
        ],

        /**
         * Configure the unauthorized strategy. It is used to render a template whenever a user is unauthorized
         */
        'unauthorized_strategy' => [
            'template' => 'error/no-auth'
        ],

        /**
         * Configure the redirect strategy. It is used to redirect the user to another route when a user is
         * unauthorized
         */
        'redirect_strategy' => [
            'redirect_to_route_connected'    => 'home',
            'redirect_to_route_disconnected' => 'zfcuser/login',
            'append_previous_uri'            => true,
            'previous_uri_query_key'         => 'redirectTo'
        ],

        /**
         * Various plugin managers for guards and role providers. Each of them must follow a common
         * plugin manager config format, and can be used to create your custom objects
         */
//        'guard_manager' => [
//            'factories' => [
//                'Application\Guard\PermissionGuard' => 'Application\Factory\PermissionGuardFactory'
//            ]
//        ],
        // 'role_provider_manager'       => []
    ]
];