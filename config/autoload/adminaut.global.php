<?php

use Core\Entity\BlogPost;
use Core\Entity\Organization;
use Zend\Router\Http\Hostname;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'adminaut' => [
        'modules' => [
            'Fuck Cancer - X' => [
                'type' => 'section',
                'label' => 'Fuck Cancer - X',
            ],
            'organizations' => [
                'type' => 'module',
                'module_name' => 'Organizace',
                'module_icon' => 'fa-hospital-o',
                'entity_class' => Organization::class,
            ],
            'blog' => [
                'type' => 'module',
                'module_name' => 'Blog',
                'module_icon' => 'fa-pencil',
                'entity_class' => BlogPost::class,
            ],
            'settings' => [
                'type' => 'link',
                'name' => 'Settings',
                'icon' => 'fa-cogs',
                'route' => ['adminaut/settings'],
            ],
        ],

        'roles' => [],

        'appearance' => [
            'skin' => 'fuck-cancer-x',
            'skin_file' => 'admin/css/skin-fuck-cancer-x.css',
            'theneColor' => '#c98bdb',
            'title' => 'Fuck Cancer - X - Admin',
            'logo' => [
                'type' => 'image',
                'large' => 'admin/img/admin-logo.svg',
                'small' => 'admin/img/admin-logo-sm.svg',
            ],
            'footer' => 'Copyright © <a href="https://www.fuck-cancer.cz/">Fuck Cancer</a>. Created by <a href="http://www.mfcc.cz/" target="_blank">Massimo Filippi</a>.',
        ],

        'manifest' => [
            'name' => 'Fuck Cancer - X - Admin',
            'show_name' => 'Fuck Cancer - X - Admin',
            'description' => '',
            'display' => 'standalone',
            'theme_color' => '#c98bdb',
            'background_color' => '#c98bdb',
            'icons' => [
                [
                    'src' => '/static/favicons/android-chrome-36x36.png',
                    'sizes' => '36x36',
                    'type' => 'image/png',
                    'density' => '0.75',
                ],
            ],
        ],

        'variables' => [
            'google-analytics' => '',
            'google-maps-api' => 'AIzaSyBRzYWkyDymkTNoA2SjjRMJpjkVqz9qpm4',
            'environment' => 'develop',
        ],

        'filesystem' => [
            'private' => [
                'adapter' => \League\Flysystem\Adapter\Local::class,
                'options' => [
                    'root' => './data/files',
                ],
            ],
            'public' => [
                'adapter' => \League\Flysystem\Adapter\Local::class,
                'options' => [
                    'root' => './www_root/_cache/files',
                    'trim' => './www_root/',
                ],
            ],
        ],
        
        'mail_service' => [
            'enabled' => true,
            'adapter' => \Core\MailModule\Adapter\Ecomail\EcomailAdapter::class,
            'adapter_params' => [
                'api_key' => '6256a1e3617c26256a1e3617c3',
            ],
            'system_name' => 'Fuck Cancer - X',
            'system_email' => 'no-reply@fuckcancer.cz',
            'templates' => [],
        ],

        'slack' => [
            'enabled' => false,
            'webhook_url' => '<webhook-url>',
            'link_names' => false,
            'allow_markdown' => true,

            'defaults' => [
                'username' => 'X - Fuck Cancer - Adminaut',
                'channel' => '#fuck-cancer-log',
                'icon' => 'https://adminaut.com/images/adminaut-favicon-64x64.png'
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'adminaut' => [
                'options' => [
                    'route' => '/admin',
                ],
                'child_routes' => [
                    'index' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/',
                            'defaults' => [
                                'controller' => Adminaut\Controller\IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'dashboard' => [
                        'options' => [
                            'route' => '/dashboard',
                        ],
                    ],
                    'api' => [
                        'options' => [
                            'route' => '/api',
                        ],
                    ],
                    'manifest' => [
                        'options' => [
                            'route' => '/manifest',
                        ],
                    ],
                    'install' => [
                        'options' => [
                            'route' => '/install',
                        ],
                    ],
                    'module' => [
                        'options' => [
                            'route' => '/module',
                        ],
                    ],
                    'users' => [
                        'options' => [
                            'route' => '/users',
                        ],
                    ],
                    'auth' => [
                        'options' => [
                            'route' => '/auth',
                        ],
                    ],
                    'profile' => [
                        'options' => [
                            'route' => '/profile',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
