<?php
namespace Frontend;

use Zend\Router\Http\Hostname;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
	'routes' => [
        'frontend' => [
            'type' => Hostname::class,
            'options' => [
                'route' => 'localhost',
            ],
            'child_routes' => [
                'index' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/',
                        'defaults' => [
                            'controller' => Controller\MapController::class,
                            'action' => 'index',
                        ],
                    ],
                ],
                'map' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/map',
                    ],
                    'child_routes' => [
                        'index' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '',
                                'defaults' => [
                                    'controller' => Controller\MapController::class,
                                    'action' => 'index',
                                ],
                            ],
                        ],
                        'load' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/load',
                                'defaults' => [
                                    'controller' => Controller\MapController::class,
                                    'action' => 'load',
                                ],
                            ],
                        ],
                        'search' => [
                            'type' => Literal::class,
                            'options' => [
                                'route' => '/search',
                                'defaults' => [
                                    'controller' => Controller\MapController::class,
                                    'action' => 'search',
                                ],
                            ],
                        ],
                    ],
                ],
                'about' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/o-portalu-x',
                        'defaults' => [
                            'controller' => Controller\PagesController::class,
                            'action' => 'about',
                        ],
                    ],
                ],
                'add-organization' => [
                    'type' => Literal::class,
                    'options' => [
                        'route' => '/pridat-organizaci',
                        'defaults' => [
                            'controller' => Controller\PagesController::class,
                            'action' => 'add-organization',
                        ],
                    ],
                ],
            ],
        ],
	],
];
