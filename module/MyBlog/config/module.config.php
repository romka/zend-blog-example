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

    'controllers' => array(
        'invokables' => array(
            'MyBlog\Controller\BlogPost' => 'MyBlog\Controller\BlogController',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'showMessages' => 'MyBlog\View\Helper\ShowMessages',
        ),
    ),


    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'MyBlog\Controller\BlogPost',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);