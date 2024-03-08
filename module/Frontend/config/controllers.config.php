<?php
namespace Frontend;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
	'aliases' => [
	],

	'invocables' => [
	],

	'factories' => [
        Controller\MapController::class => Controller\Factory\MapControllerFactory::class,
        Controller\PagesController::class => Controller\Factory\PagesControllerFactory::class,
	],
];
