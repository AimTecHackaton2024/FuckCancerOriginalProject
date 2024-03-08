<?php
namespace Core;


return [
	'aliases' => [
        'config' => View\Helper\ConfigViewHelper::class,
	],

	'invokables' => [
        'datatypeFormSelect' => Datatype\Select\FormViewHelper::class,
        'datatypeFormLocation' => Datatype\Location\FormViewHelper::class,
	],

	'factories' => [
        View\Helper\ConfigViewHelper::class => View\Helper\Factory\ConfigViewHelperFactory::class,
	],
];
