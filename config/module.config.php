<?php

namespace Midnight\Settings;

return [
    'router' => [
        'routes' => [
            'zfcadmin' => [
                'child_routes' => [
                    strtolower(__NAMESPACE__) => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/einstellungen',
                            'defaults' => [
                                'controller' => __NAMESPACE__ . '\Controller\Admin',
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            __NAMESPACE__ . '\Controller\Admin' => __NAMESPACE__ . '\Controller\AdminController',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '-driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/' . str_replace('\\', DIRECTORY_SEPARATOR, __NAMESPACE__) . '/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ => __NAMESPACE__ . '-driver',
                ],
            ],
        ],
    ],
    'navigation' => [
        'admin' => [
            strtolower(__NAMESPACE__) => [
                'label' => 'Einstellungen',
                'route' => 'zfcadmin/' . strtolower(__NAMESPACE__),
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => array(
        'invokables' => array(
            'settings' => 'Midnight\Settings\Service\SettingService',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'settings' => 'Midnight\Settings\Mvc\Controller\Plugin\SettingsPlugin',
        ),
    ),
];