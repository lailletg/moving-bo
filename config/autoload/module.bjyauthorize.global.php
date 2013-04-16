<?php
return array(

    'controllers' => array(
        'invokables' => array(
            'zfcuser' => 'ZfcUser\Controller\UserController',
            'Admin' => 'Admin\Controller\AdminController',
        ),
    ),
    'bjyauthorize' => array(

        // set the 'guest' role as default (must be defined in a role provider)
        'default_role' => 'Visiteur',

        'authenticated_role'    => 'Utilisateur',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'SamUser\Entity\Role',
            ),
        ),
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
                array(
                    'controller' => 'Application\Controller\Index',
                    'action' => 'index',
                    'roles' => array()
                ),
                array(
                    'controller' => 'zfcuser',
                    'roles' => array()
                ),
                array(
                    'controller' => 'zfcuser',
                    'action' => 'register', 
                    'roles' => array('Visiteur')
                ),
                array(
                    'controller' => 'Admin',
                    'roles' => array('Admin')
                ),
                array(
                    'controller' => 'Admin',
                    'action' => 'editusers',
                    'roles' => array('Admin')
                ),
                array(
                    'controller' => 'Admin',
                    'action' => 'changeRole',
                    'roles' => array('Admin')
                ),
                /*
                array(
                    'controller' => 'Mail',
                    'roles' => array('Utilisateur')
                ),*/
            ),

            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'home', 'roles' => array('Visiteur')),

                array('route' => 'admin', 'roles' => array('Admin')),
                array('route' => 'admin/gestion-users', 'roles' => array('Admin')),
                array('route' => 'admin/changeRole', 'roles' => array('Admin')),

                array('route' => 'zfcuser', 'roles' => array('Utilisateur')),
                array('route' => 'zfcuser/logout', 'roles' => array('Utilisateur')),
                array('route' => 'zfcuser/login', 'roles' => array('Visiteur')),
                array('route' => 'zfcuser/register', 'roles' => array('Visiteur')),
                array('route' => 'zfcuser/changepassword', 'roles' => array('Utilisateur')),
                array('route' => 'zfcuser/changeemail', 'roles' => array('Utilisateur')),
                /*
                array('route' => 'mail/admin', 'roles' => array('Utilisateur')),
                array('route' => 'mail/collection', 'roles' => array('Utilisateur')),
                array('route' => 'mail/parcours', 'roles' => array('Utilisateur')),*/
            ),
        ), 
    ),
);