<?php
namespace Application;

return [
    'driver' => [
        'app_driver' => [
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'array',
            'paths' => [
                __DIR__ . '/../src/Entity',
            ],
        ],
        'orm_default' => [
            'drivers' => [
                'Core\Entity' => 'app_driver',
            ],
        ],
    ],


    'configuration' => [
        'orm_default' => [
            'numeric_functions' => [
                'INSTR' => 'Core\Doctrine\Functions\Instr',
            ],
        ]
    ],
];
