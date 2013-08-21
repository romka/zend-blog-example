<?php
return array(
    'doctrine' => array(
        'driver' => array(
            'myblog_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/MyBlog/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'MyBlog\Entity' => 'myblog_entity',
                )
            )
        )
    ),
);