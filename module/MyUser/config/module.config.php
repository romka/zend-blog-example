<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'zfcuser_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyUser/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'MyUser\Entity' => 'zfcuser_entity',
                )
            )
        )
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'MyUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),

    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entity_manager.orm_default',
                'role_entity_class' => 'MyUser\Entity\Role',
            ),
        ),
    ),
);
