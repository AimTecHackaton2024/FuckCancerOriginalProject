<?php
namespace Admin;

use Admin\Controller\SettingsController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
	'routes' => [
        'adminaut' => [
            'child_routes' => [
                'settings' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/settings',
                        'defaults' => [
                            'controller' => SettingsController::class,
                            'action' => 'index',
                        ],
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'list' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id',
                                'defaults' => [
                                    'action' => 'list',
                                ],
                            ],
                        ],
                        'add' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id/add',
                                'defaults' => [
                                    'action' => 'add',
                                ],
                            ],
                        ],
                        'edit' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id/edit/:entity_id',
                                'defaults' => [
                                    'action' => 'edit',
                                ],
                            ],
                        ],
                        'delete' => [
                            'type' => Segment::class,
                            'options' => [
                                'route' => '/:module_id/delete/:entity_id',
                                'defaults' => [
                                    'action' => 'delete',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
	],
];
